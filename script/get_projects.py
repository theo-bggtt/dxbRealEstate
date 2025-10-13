import requests
import datetime
import os
import pandas as pd
import logging

# Configure logging
logging.basicConfig(filename='download_dubai_csv.log', level=logging.INFO, format='%(asctime)s - %(levelname)s - %(message)s')

# Define the URL and headers
url = "https://gateway.dubailand.gov.ae/open-data/projects/export/csv"
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

# Define the base payload with fixed date range
base_payload = {
    "parameters": {
        "P_DATE_TYPE": "1",
        "P_FROM_DATE": "01/01/2025",
        "P_TO_DATE": "10/14/2025",
        "P_AREA_ID": "",
        "P_PRJ_STATUS": "",
        "P_PRJ_TYPE_ID": "",
        "P_ZONE_ID": "",
        "P_TAKE": "100",  # Fetch 100 records per request
        "P_SKIP": "0",    # Start at 0, increment for pagination
        "P_SORT": "PROJECT_NUMBER_ASC"
    },
    "command": "projects",
    "labels": {
        "PROJECT_NUMBER": "PROJECT_NUMBER",
        "PROJECT_EN": "PROJECT_EN",
        "DEVELOPER_NUMBER": "DEVELOPER_NUMBER",
        "DEVELOPER_EN": "DEVELOPER_EN",
        "START_DATE": "START_DATE",
        "END_DATE": "END_DATE",
        "ADOPTION_DATE": "ADOPTION_DATE",
        "PRJ_TYPE_EN": "PRJ_TYPE_EN",
        "PROJECT_VALUE": "PROJECT_VALUE",
        "ESCROW_ACCOUNT_NUMBER": "ESCROW_ACCOUNT_NUMBER",
        "PROJECT_STATUS": "PROJECT_STATUS",
        "PERCENT_COMPLETED": "PERCENT_COMPLETED",
        "INSPECTION_DATE": "INSPECTION_DATE",
        "COMPLETION_DATE": "COMPLETION_DATE",
        "DESCRIPTION_EN": "DESCRIPTION_EN",
        "AREA_EN": "AREA_EN",
        "ZONE_EN": "ZONE_EN",
        "CNT_LAND": "CNT_LAND",
        "CNT_BUILDING": "CNT_BUILDING",
        "CNT_VILLA": "CNT_VILLA",
        "CNT_UNIT": "CNT_UNIT",
        "MASTER_PROJECT_EN": "MASTER_PROJECT_EN"
    }
}

# Initialize variables for pagination
all_dataframes = []
skip = 0
take = 100
has_more_data = True
timestamp = datetime.datetime.now().strftime('%Y%m%d_%H%M%S')
filename = f"dubai_developers_projects_{timestamp}.csv"

while has_more_data:
    # Update P_SKIP for the current batch
    base_payload["parameters"]["P_SKIP"] = str(skip)
    
    # Send the POST request
    try:
        response = requests.post(url, headers=headers, json=base_payload, timeout=30)
        response.raise_for_status()  # Raise an error for bad status codes
    except requests.exceptions.RequestException as e:
        logging.error(f"Request failed: {e}")
        print(f"Failed to download batch at skip {skip}: {e}")
        break

    # Check if the response contains CSV data
    if response.status_code == 200 and response.content:
        # Load the CSV data into a pandas DataFrame
        from io import StringIO
        csv_data = StringIO(response.text)
        df = pd.read_csv(csv_data)
        
        if not df.empty:
            all_dataframes.append(df)
            logging.info(f"Fetched {len(df)} records at skip {skip}")
            print(f"Fetched {len(df)} records at skip {skip}")
            skip += take  # Move to the next batch
        else:
            logging.info("No more data to fetch")
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

# Optional: Print unique developers and projects for verification
if all_dataframes:
    developers = final_df[['DEVELOPER_EN', 'PROJECT_EN']].drop_duplicates()
    logging.info(f"Found {len(developers)} unique developer-project pairs")
    print(f"Found {len(developers)} unique developer-project pairs")