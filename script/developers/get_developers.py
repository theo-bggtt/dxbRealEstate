import requests
import datetime
import pandas as pd
import logging
from io import StringIO

# Configure logging
logging.basicConfig(filename='download_dubai_csv.log', level=logging.DEBUG, format='%(asctime)s - %(levelname)s - %(message)s')

# Define the URL and headers
url = "https://gateway.dubailand.gov.ae/open-data/developers/export/csv"
headers = {
    "accept": "*/*",
    "accept-language": "fr-FR,fr;q=0.7",
    "content-type": "application/json; charset=UTF-8",
    "sec-ch-ua": "\"Brave\";v=\"141\", \"Not?A_Brand\";v=\"8\", \"Chromium\";v=\"141\"",
    "sec-ch-ua-mobile": "?0",
    "sec-ch-ua-platform": "\"Windows\"",
    "sec-fetch-dest": "empty",
    "sec-fetch-mode": "cors",
    "sec-fetch-site": "same-site",
    "sec-gpc": "1"
}

# Define the base payload
base_payload = {
    "parameters": {
        "P_NAME": "",
        "P_FROM_DATE": "01/01/2025",
        "P_TO_DATE": "10/31/2025",
        "P_TAKE": "100",  # Fetch 100 records per request
        "P_SKIP": "0",
        "P_SORT": "DEVELOPER_NUMBER_ASC"
    },
    "command": "developers",
    "labels": {
        "DEVELOPER_NUMBER": "DEVELOPER_NUMBER",
        "DEVELOPER_EN": "DEVELOPER_EN",
        "REGISTRATION_DATE": "REGISTRATION_DATE",
        "LICENSE_SOURCE_EN": "LICENSE_SOURCE_EN",
        "LICENSE_TYPE_EN": "LICENSE_TYPE_EN",
        "LEGAL_STATUS_EN": "LEGAL_STATUS_EN",
        "WEBPAGE": "WEBPAGE",
        "PHONE": "PHONE",
        "FAX": "FAX",
        "LICENSE_NUMBER": "LICENSE_NUMBER",
        "LICENSE_ISSUE_DATE": "LICENSE_ISSUE_DATE",
        "LICENSE_EXPIRY_DATE": "LICENSE_EXPIRY_DATE",
        "CHAMBER_OF_COMMERCE_NO": "CHAMBER_OF_COMMERCE_NO"
    }
}

# Initialize variables for pagination
all_dataframes = []
skip = 0
take = 100
has_more_data = True
timestamp = datetime.datetime.now().strftime('%Y%m%d_%H%M%S')
filename = f"dubai_developers_{timestamp}.csv"

while has_more_data:
    # Update P_SKIP for the current batch
    base_payload["parameters"]["P_SKIP"] = str(skip)
    
    # Send the POST request
    try:
        logging.debug(f"Sending request with P_SKIP={skip}, P_TAKE={take}")
        response = requests.post(url, headers=headers, json=base_payload, timeout=30)
        response.raise_for_status()  # Raise an error for bad status codes
    except requests.exceptions.RequestException as e:
        logging.error(f"Request failed at skip {skip}: {e}")
        print(f"Failed to download batch at skip {skip}: {e}")
        break

    # Log the raw response for debugging
    logging.debug(f"Response status: {response.status_code}, Response text: {response.text[:1000]}")  # Truncate for brevity

    # Check if the response contains CSV data
    if response.status_code == 200 and response.content:
        # Load the CSV data into a pandas DataFrame
        csv_data = StringIO(response.text)
        try:
            df = pd.read_csv(csv_data)
            if not df.empty:
                all_dataframes.append(df)
                logging.info(f"Fetched {len(df)} records at skip {skip}")
                print(f"Fetched {len(df)} records at skip {skip}")
                skip += take  # Move to the next batch
            else:
                logging.info("No more data to fetch")
                print("No more data to fetch")
                has_more_data = False
        except pd.errors.EmptyDataError:
            logging.error("Response contains empty or malformed CSV")
            print("Response contains empty or malformed CSV")
            has_more_data = False
    else:
        logging.error(f"Failed to download batch at skip {skip}. Status code: {response.status_code}, Response: {response.text}")
        print(f"Failed to download batch at skip {skip}. Status code: {response.status_code}")
        has_more_data = False

# Combine all DataFrames and save to CSV
if all_dataframes:
    final_df = pd.concat(all_dataframes, ignore_index=True)
    final_df.to_csv(filename, index=False)
    logging.info(f"Saved {len(final_df)} records to {filename}")
    print(f"Saved {len(final_df)} records to {filename}")
else:
    logging.warning("No data was fetched")
    print("No data was fetched")

# Optional: Print unique developers for verification
if all_dataframes:
    unique_developers = final_df[['DEVELOPER_NUMBER', 'DEVELOPER_EN']].drop_duplicates()
    logging.info(f"Found {len(unique_developers)} unique developers")
    print(f"Found {len(unique_developers)} unique developers")