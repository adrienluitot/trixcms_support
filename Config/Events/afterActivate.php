<?php

use App\TModels\Permission;
use Illuminate\Database\Eloquent\Model;

$supportPermissions = [
    'DASHBOARD_SUPPORT_VIEW_CATEGORIES',
    'DASHBOARD_SUPPORT_ADD_CATEGORY',
    'DASHBOARD_SUPPORT_DELETE_CATEGORY',
    'DASHBOARD_SUPPORT_EDIT_CATEGORY',

    'DASHBOARD_SUPPORT_VIEW_TICKET',
    'DASHBOARD_SUPPORT_EDIT_TICKET',
    'DASHBOARD_SUPPORT_ANSWER_TICKET',
    'DASHBOARD_SUPPORT_DELETE_TICKET',
];

Model::unguard();

foreach ($supportPermissions as $permission) {
    Permission::addOrUpdate($permission, "support_alfiory::admin.permission_" . $permission, "plugin", "930442654");
}

Model::reguard();