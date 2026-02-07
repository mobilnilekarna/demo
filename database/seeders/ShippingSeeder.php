<?php

namespace Database\Seeders;

use App\Enums\PaymentType;
use App\Enums\TransportType;
use App\Models\Payment;
use App\Models\Shipping;
use App\Models\Transport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ShippingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vypnout kontrolu cizích klíčů
        Schema::disableForeignKeyConstraints();

        // Vyčistit stávající záznamy
        Shipping::truncate();

        Schema::enableForeignKeyConstraints();

        // Získat všechny transports a payments
        $transports = Transport::all();
        $payments = Payment::all();

        // Vytvořit pouze validní kombinace transports a payments
        foreach ($transports as $transport) {
            foreach ($payments as $payment) {
                // Zkontrolovat, zda je kombinace validní
                if (!$this->isValidCombination($transport->type, $payment->type)) {
                    continue;
                }

                // Získat specifickou cenu pro kombinaci (pokud existuje)
                $price = $this->getShippingPrice($transport->type, $payment->type);

                Shipping::create([
                    'transport_id' => $transport->id,
                    'payment_id' => $payment->id,
                    'price' => $price, // null = použije se součet cen transportu a platby
                ]);
            }
        }
    }

    /**
     * Zkontroluje, zda je kombinace transportu a platby validní
     */
    private function isValidCombination(string $transportType, string $paymentType): bool
    {
        // Osobní odběr (personal) - NEMŮŽE být dobírka (COD)
        if ($transportType === TransportType::PERSONAL->value) {
            return $paymentType !== PaymentType::COD->value;
        }

        // Kurýrní služby (PPL, DHL, DPD) - NEMŮŽE být hotovost (CASH)
        $courierTypes = [
            TransportType::PPL->value,
            TransportType::PPL_PARCEL->value,
            TransportType::DHL->value,
            TransportType::DPD->value,
            TransportType::DPD_PICKUP->value,
        ];

        if (in_array($transportType, $courierTypes)) {
            return $paymentType !== PaymentType::CASH->value;
        }

        // Česká pošta a výdejní místa - všechny platby jsou povolené
        // (CP_NP, CP_DR, CP_BA, PACKETA_CZ, PACKETA_SK)

        return true;
    }

    /**
     * Vrátí specifickou cenu pro kombinaci transportu a platby
     * Pokud vrátí null, použije se součet cen z transports a payments tabulek
     */
    private function getShippingPrice(string $transportType, string $paymentType): ?float
    {
        // Osobní odběr je vždy zdarma, bez ohledu na platbu
        if ($transportType === TransportType::PERSONAL->value) {
            return 0.00;
        }

        // Výchozí: null = použije se součet cen
        return null;
    }
}
