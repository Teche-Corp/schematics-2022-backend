<?php

namespace App\Core\Domain\Models\Pembayaran;

enum TipePembayaran: string
{
    case NLC_TEAM = 'nlc_team';
    case NPC_TEAM = 'npc_team';
    case NST_ORDER = 'nst_order';
    case REEVA_ORDER = 'reeva_order';

    case MIDTRANS_REEVA_ORDER = 'midtrans_reeva_order';
    case MIDTRANS_NST_ORDER = 'midtrans_nst_order';
}
