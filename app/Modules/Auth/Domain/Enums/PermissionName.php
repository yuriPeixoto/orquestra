<?php

namespace App\Modules\Auth\Domain\Enums;

enum PermissionName: string
{
    // Platform
    case ManagePlatform = 'manage_platform';

    // Workspace
    case CreateWorkspace = 'create_workspace';
    case ManageWorkspace = 'manage_workspace';
    case DeleteWorkspace = 'delete_workspace';
    case InviteMembers = 'invite_members';
    case RemoveMembers = 'remove_members';
    case AssignRoles = 'assign_roles';

    // Initiatives
    case CreateInitiative = 'create_initiative';
    case EditInitiative = 'edit_initiative';
    case DeleteInitiative = 'delete_initiative';
    case ViewInitiative = 'view_initiative';

    // Decisions
    case CreateDecision = 'create_decision';
    case EditDecision = 'edit_decision';
    case DeleteDecision = 'delete_decision';
    case ViewDecision = 'view_decision';
}
