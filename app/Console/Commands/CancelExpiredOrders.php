<?php
namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class CancelExpiredOrders extends Command
{
    protected $signature = 'orders:cancel-expired';
    protected $description = 'Cancela pedidos pending expirados';

    public function handle()
    {
        Order::where('status', 'pending')
            ->where('ordered_at', '<', now()->subMinutes(1))
            ->update(['status' => 'cancelled']);

        $this->info('Pedidos expirados cancelados.');
    }
}