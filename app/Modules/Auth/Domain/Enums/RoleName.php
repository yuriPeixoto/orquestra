<?php

namespace App\Modules\Auth\Domain\Enums;

enum RoleName: string
{
    case Admin = 'admin';
    case WorkspaceOwner = 'workspace_owner';
    case WorkspaceMember = 'workspace_member';
    case WorkspaceViewer = 'workspace_viewer';
}
