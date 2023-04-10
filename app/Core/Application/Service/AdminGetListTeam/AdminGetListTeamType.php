<?php

namespace App\Core\Application\Service\AdminGetListTeam;

enum AdminGetListTeamType: string
{
    case NPC_JUNIOR = 'npc_junior';
    case NPC_SENIOR = 'npc_senior';
    case NLC = 'nlc';
}
