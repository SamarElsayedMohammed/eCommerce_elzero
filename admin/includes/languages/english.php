<?php

function lang($phrase)
{

    static $lang = array(
        'ADMIN_HOME' => 'admin panel',
        'CATEGORIES' => 'Categories',
        'ITEMS'=>'Items',
        'MEMBERS'=>'Members',
        'COMMENTS' => 'Comments',
        'STATISTICS'=>'Statistics',
        'LOGS'=>'logs',
        'EDIT_PROFILE' => 'Edit Profile',
        'SETTINGS' => 'Settings',
        'LOGOUT' => 'Log Out',
        'VISIT_WEBSITE' => 'Visit Website',
        '' => '',
        '' => '',
        '' => '',
        '' => '',
    );

    return $lang[$phrase];
}
