<?php

function lang($phrase)
{

    static $lang = array(
        'HOME' => 'HomePage',
        'CATEGORIES' => 'Categories',
        'ITEMS'=>'Items',
        'MEMBERS'=>'Members',
        'COMMENTS' => 'Comments',
        'STATISTICS'=>'Statistics',
        'LOGS'=>'logs',
        'EDIT_PROFILE' => 'Edit Profile',
        'SETTINGS' => 'Settings',
        'LOGOUT' => 'Log Out',
        '' => '',
        '' => '',
        '' => '',
        '' => '',
        '' => '',
    );

    return $lang[$phrase];
}
