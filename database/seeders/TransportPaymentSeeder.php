<?php

namespace Database\Seeders;

use App\Enums\DeliveryType;
use App\Enums\PaymentType;
use App\Enums\TransportType;
use App\Models\Payment;
use App\Models\Transport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class TransportPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vypnout kontrolu cizích klíčů
        Schema::disableForeignKeyConstraints();

        // Vyčistit stávající záznamy
        Transport::truncate();
        Payment::truncate();

        Schema::enableForeignKeyConstraints();

        // Naplnit transports na základě TransportType enumu
        $transportOrder = 1;
        foreach (TransportType::cases() as $transportType) {
            Transport::create([
                'name' => $transportType->name(),
                'description' => $this->getTransportDescription($transportType),
                'price' => $this->getTransportPrice($transportType),
                'free_from' => $this->getTransportFreeFrom($transportType),
                'order' => $transportOrder++,
                'active' => true,
                'type' => $transportType->value,
                'type_delivery' => $transportType->deliveryType()->value,
            ]);
        }

        // Naplnit payments na základě PaymentType enumu
        $paymentOrder = 1;
        foreach (PaymentType::cases() as $paymentType) {
            Payment::create([
                'name' => $paymentType->name(),
                'description' => $this->getPaymentDescription($paymentType),
                'price' => $this->getPaymentPrice($paymentType),
                'order' => $paymentOrder++,
                'active' => true,
                'type' => $paymentType->value,
            ]);
        }
    }

    /**
     * Vrátí popis pro typ dopravy
     */
    private function getTransportDescription(TransportType $type): ?string
    {
        return match($type) {
            TransportType::PERSONAL => 'Osobní odběr zákazníka na pobočce',
            TransportType::CP_NP => 'Doručení na pobočku České pošty',
            TransportType::CP_DR => 'Doručení na adresu Českou poštou',
            TransportType::CP_BA => 'Doručení do balíkovny České pošty',
            TransportType::PPL => 'Doručení kurýrní službou PPL',
            TransportType::PPL_PARCEL => 'Doručení do PPL Parcel Shop',
            TransportType::DHL => 'Doručení kurýrní službou DHL',
            TransportType::PACKETA_CZ => 'Doručení do výdejního místa Packeta (ČR)',
            TransportType::PACKETA_SK => 'Doručení do výdejního místa Packeta (SR)',
            TransportType::DPD => 'Doručení kurýrní službou DPD',
            TransportType::DPD_PICKUP => 'Doručení do DPD Pickup místa',
        };
    }

    /**
     * Vrátí výchozí cenu pro typ dopravy
     */
    private function getTransportPrice(TransportType $type): float
    {
        return match($type) {
            TransportType::PERSONAL => 0.00,
            TransportType::CP_NP => 99.00,
            TransportType::CP_DR => 129.00,
            TransportType::CP_BA => 99.00,
            TransportType::PPL => 149.00,
            TransportType::PPL_PARCEL => 99.00,
            TransportType::DHL => 199.00,
            TransportType::PACKETA_CZ => 89.00,
            TransportType::PACKETA_SK => 119.00,
            TransportType::DPD => 149.00,
            TransportType::DPD_PICKUP => 99.00,
        };
    }

    /**
     * Vrátí cenu zdarma od pro typ dopravy
     */
    private function getTransportFreeFrom(TransportType $type): ?float
    {
        return match($type) {
            TransportType::PERSONAL => null, // Osobní odběr je vždy zdarma
            TransportType::CP_NP => 1500.00,
            TransportType::CP_DR => 1500.00,
            TransportType::CP_BA => 1500.00,
            TransportType::PPL => 2000.00,
            TransportType::PPL_PARCEL => 1500.00,
            TransportType::DHL => 2500.00,
            TransportType::PACKETA_CZ => 1500.00,
            TransportType::PACKETA_SK => 1500.00,
            TransportType::DPD => 2000.00,
            TransportType::DPD_PICKUP => 1500.00,
        };
    }

    /**
     * Vrátí popis pro typ platby
     */
    private function getPaymentDescription(PaymentType $type): ?string
    {
        return match($type) {
            PaymentType::COD => 'Platba při převzetí zboží',
            PaymentType::CASH => 'Platba hotovostí',
            PaymentType::CARD => 'Platba platební kartou online',
            PaymentType::BANK => 'Platba bankovním převodem',
            PaymentType::BENEFIT_CARD => 'Platba benefit kartou',
        };
    }

    /**
     * Vrátí výchozí cenu pro typ platby
     */
    private function getPaymentPrice(PaymentType $type): float
    {
        return match($type) {
            PaymentType::COD => 29.00, // Poplatek za dobírku
            PaymentType::CASH => 0.00,
            PaymentType::CARD => 0.00,
            PaymentType::BANK => 0.00,
            PaymentType::BENEFIT_CARD => 0.00,
        };
    }
}
