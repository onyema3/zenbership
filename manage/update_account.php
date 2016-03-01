<?php

/**
 *    Zenbership
 *    http://www.zenbership.com/
 *    (c) 2012, Castlamp.
 *
 *    Purpose: User management page:
 *    -> Update Account
 *
 *    WARNING!
 *    DO NOT EDIT THIS FILE!
 *    To change the calendar's
 *    apperance, please edit the
 *    program templates from the
 *    "Integration" section of the
 *    admin control panel.
 *
 */
// Load the basics
require "../admin/sd-system/config.php";
// Check a user's session
$session = new session;
$ses     = $session->check_session();
if ($ses['error'] == '1') {
    $session->reject('login', $ses['ecode']);
    exit;
} else {
    // Type of update
    if (!empty($_GET['t'])) {
        $type = 'periodical';
    } else {
        $type = '';
    }
    // Redirect
    if (!empty($_GET['follow'])) {
        $redirect = $_GET['follow'];
    } else {
        $redirect = '';
    }
    // Member
    $user   = new user;
    $member = $user->get_user($ses['member_id']);
    // Form session
    $form = new form('', 'update', 'account', $ses['member_id'], '', '1');
    $form->start_session();
    // Fieldsets
    $fields    = new field;
    $form_main = $fields->generate_form('update-account', $member['data']);
    // Additional fieldsets?
    $add_sets = $user->get_area_access_ids($member);
    // Additional fieldsets pertinant
    // only to the content this user
    // has access to.
    $custom_forms = '';
    foreach ($add_sets as $fid) {
        $custom_forms .= $fields->generate_field_set($fid, '', $member['data']);
    }
    // Template
    $changes = array(
        'form'         => $form_main,
        'custom_forms' => $custom_forms,
        'type'         => $type,
        'redirect'     => $redirect,
        'form_session' => $form->{'session_id'},
    );
    $wrapper = new template('manage_update_account', $changes, '1');
    echo $wrapper;
    exit;
}
