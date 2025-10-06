<nav>
    <a href="index.php?langue=<?php echo($_SESSION['langue'])?>"><?php echo $lang['MAINPAGE_TITLE'];?></a>
    <a href="developers.php?langue=<?php echo($_SESSION['langue'])?>"><?php echo $lang['NAV_DEV_TITLE'];?></a>
    <a href="projects.php?langue=<?php echo($_SESSION['langue'])?>"><?php echo $lang['NAV_PROJECTS_TITLE'];?></a>
    <a href="contact.php?langue=<?php echo($_SESSION['langue'])?>"><?php echo $lang['NAV_CONTACT_TITLE'];?></a>
</nav>
<h1><?php echo $lang['MAINPAGE_TITLE'];?></h1>
<div>
    <form action="" method="GET">
    <button type="submit" name="langue" value="fr">Français</button>
    <button type="submit" name="langue" value="en">English</button>
    </form>
</div>