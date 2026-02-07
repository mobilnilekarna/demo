<?php

namespace App\Interface;

use App\Enums\ImportSourceType;

/**
 * Rozhraní pro dodavatele a zpracování jejich ceníků.
 *
 * Způsob získání ceníku:
 * - api   – stáhnutí přes API (endpoint)
 * - ftp   – soubor nahraný na FTP (vždy jen nejnovější v cílové složce)
 * - url   – import z URL adresy (feed)
 *
 * Pravidla zpracování:
 * - Zpracovává se vždy pouze nejnovější soubor/feed.
 * - Po úspěšném zpracování se soubor přesune do složky „přečtené“ (readed).
 * - U API se nic nepřesouvá, pouze se označí jako zpracované.
 */
interface SupplierInterface
{
    /**
     * Typ zdroje importu dle \App\Enums\ImportSourceType
     */
    public function getSourceType(): ImportSourceType;

    /**
     * Zkontroluje dostupnost zdroje a případnou existenci nových souborů.
     * Např. ping API, kontrola FTP složky, dostupnost feed URL.
     */
    public function check(): void;

    /**
     * Načte / stáhne ceník. Vždy pracuje pouze s nejnovějším souborem nebo feedem.
     * - API: volá endpoint a vrací odpověď
     * - FTP: vybere nejnovější soubor v dané složce a vrátí cestu/stream
     * - URL: stáhne feed z adresy a vrátí obsah nebo cestu k dočasnému souboru
     * - EMAIL: stáhne soubor z emailu a vrátí cestu k dočasnému souboru
     * - FILE: stáhne soubor z lokálního disku a vrátí cestu k dočasnému souboru
     * - WEBHOOK: stáhne soubor z webhooku a vrátí cestu k dočasnému souboru
     * - MANUAL: klient nahraje vlastní zdroj produktů
     *
     * @return string|resource|array|null Cesta k souboru, stream, parsovaná data nebo null
     */
    public function fetchLatest(): mixed;

    /**
     * Zpracuje načtený ceník (parsování, validace, import do DB).
     *
     * @param mixed $source Výstup z fetchLatest() – soubor, stream nebo data
     */
    public function process(mixed $source = null): void;

    /**
     * Po úspěšném zpracování přesune soubor do složky přečtené (readed).
     * U API nemusí nic přesouvat – pouze označí záznam jako zpracovaný.
     */
    public function moveToRead(): void;

    /**
     * Kontrola, zda je u zdroje v cílové tabulce více než X produktů.
     * Slouží k ověření, že import proběhl v pořádku.
     */
    public function hasEnough(): void;

    /**
     * Je dodavatel aktivní pro import ceníků?
     */
    public function isActive(): bool;
}
