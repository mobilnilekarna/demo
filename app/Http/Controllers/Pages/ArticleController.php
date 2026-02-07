<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Získání všech článků
     */
    public static function getAllArticles()
    {
        $articles = [
            [
                'id' => 1,
                'title' => 'Jak vybrat správný notebook pro práci z domova',
                'excerpt' => 'Kompletní průvodce výběrem notebooku pro home office. Zjistěte, na co se zaměřit při nákupu.',
                'content' => 'Práce z domova se stala běžnou součástí našeho života a správný notebook je klíčový pro produktivní práci. V tomto článku se podíváme na všechny důležité aspekty výběru notebooku pro home office.

## Procesor a výkon

Pro běžnou kancelářskou práci postačí procesor Intel Core i5 nebo AMD Ryzen 5. Pokud však pracujete s náročnějšími aplikacemi nebo multitaskingem, zvažte Core i7 nebo Ryzen 7.

## Paměť RAM

Minimálně 8 GB RAM je dnes standardem, ale pro pohodlnou práci doporučujeme 16 GB. To zajistí plynulý chod i při práci s více aplikacemi současně.

## Úložiště

SSD disk je dnes nezbytností. Minimálně 256 GB, ideálně 512 GB nebo více. SSD zajistí rychlý start systému a aplikací.

## Displej

Pro práci z domova je důležitá kvalita displeje. Doporučujeme Full HD rozlišení (1920x1080) nebo vyšší. Velikost obrazovky závisí na vašich preferencích, ale 13-15 palců je ideální kompromis mezi přenositelností a komfortem.

## Baterie

Dlouhá výdrž baterie je důležitá pro flexibilitu. Hledejte notebooky s výdrží alespoň 8 hodin při běžné práci.

## Klávesnice a touchpad

Protože budete notebook používat denně, kvalitní klávesnice a touchpad jsou nezbytné. Pokud je to možné, vyzkoušejte si notebook před nákupem.

## Závěr

Výběr správného notebooku pro práci z domova závisí na vašich konkrétních potřebách a rozpočtu. Zaměřte se na výkon, paměť, úložiště a kvalitu displeje. Nezapomeňte také na ergonomii a výdrž baterie.',
                'image' => '/images/placeholder/article-1.jpg',
                'date' => '2024-01-15',
                'author' => 'Jan Novák',
            ],
            [
                'id' => 2,
                'title' => '5 tipů pro lepší produktivitu s novým smartphonem',
                'excerpt' => 'Naučte se využívat všechny funkce vašeho nového smartphonu na maximum. Praktické tipy a triky.',
                'content' => 'Nový smartphone může výrazně zvýšit vaši produktivitu, pokud víte, jak ho správně používat. Zde je 5 praktických tipů, které vám pomohou využít jeho potenciál naplno.

## 1. Organizace aplikací

Vytvořte si složky podle kategorií aplikací. Například: Práce, Sociální sítě, Zábava, Nástroje. To vám pomůže rychle najít potřebnou aplikaci a udržet si přehled.

## 2. Notifikace pod kontrolou

Nastavte si notifikace tak, aby vás nerušily zbytečně. Vypněte notifikace pro aplikace, které nejsou důležité, a nastavte si tiché hodiny pro čas odpočinku.

## 3. Používejte hlasové příkazy

Moderní smartphony mají pokročilé hlasové asistenty. Naučte se je používat pro rychlé úkoly jako nastavení připomínek, odesílání zpráv nebo vyhledávání informací.

## 4. Cloudová synchronizace

Nastavte si automatickou synchronizaci důležitých dat do cloudu. To zajistí, že vaše data budou v bezpečí a dostupná na všech zařízeních.

## 5. Pravidelné aktualizace

Udržujte svůj smartphone aktualizovaný. Aktualizace často přinášejí nové funkce, opravy bezpečnostních chyb a zlepšení výkonu.

## Závěr

Správné nastavení a používání smartphonu může výrazně zvýšit vaši produktivitu. Začněte s těmito tipy a postupně objevujte další možnosti vašeho zařízení.',
                'image' => '/images/placeholder/article-2.jpg',
                'date' => '2024-01-10',
                'author' => 'Marie Svobodová',
            ],
            [
                'id' => 3,
                'title' => 'Tablety vs. Notebooky: Co je lepší pro vás?',
                'excerpt' => 'Porovnání tabletů a notebooků. Zjistěte, které zařízení je pro vás ideální volbou.',
                'content' => 'Rozhodování mezi tabletem a notebookem může být složité. Oba typy zařízení mají své výhody a nevýhody. V tomto článku vám pomůžeme najít ideální řešení pro vaše potřeby.

## Tablety - výhody

Tablety jsou lehké, přenositelné a mají dlouhou výdrž baterie. Jsou ideální pro čtení, sledování videí, prohlížení webu a základní práci. Dotykové ovládání je intuitivní a moderní tablety podporují stylus pro přesnější práci.

## Notebooky - výhody

Notebooky nabízejí plnohodnotný operační systém, výkonnější hardware a fyzickou klávesnici. Jsou ideální pro náročnější úkoly, programování, psaní dlouhých textů a práci s více aplikacemi současně.

## Kdy zvolit tablet?

Tablet je vhodný, pokud:
- Potřebujete lehké a přenositelné zařízení
- Hlavně čtete, sledujete videa a prohlížíte web
- Děláte základní práci s dokumenty
- Chcete zařízení s dlouhou výdrží baterie

## Kdy zvolit notebook?

Notebook je vhodný, pokud:
- Potřebujete výkon pro náročnější aplikace
- Píšete často dlouhé texty
- Programujete nebo pracujete s grafikou
- Potřebujete plnohodnotný operační systém

## Hybridní řešení

Některé tablety s klávesnicí (např. iPad Pro s Magic Keyboard nebo Surface Pro) mohou být kompromisem mezi oběma světy. Nabízejí výhody tabletů s možností produktivní práce.

## Závěr

Výběr závisí na vašich konkrétních potřebách. Pro mobilitu a zábavu zvolte tablet, pro produktivní práci notebook. Nebo zvažte hybridní řešení, které kombinuje oba přístupy.',
                'image' => '/images/placeholder/article-3.jpg',
                'date' => '2024-01-05',
                'author' => 'Petr Dvořák',
            ],
            [
                'id' => 4,
                'title' => 'Bezdrátová sluchátka: Recenze nejlepších modelů 2024',
                'excerpt' => 'Přehled nejlepších bezdrátových sluchátek na trhu. Porovnání funkcí, kvality zvuku a ceny.',
                'content' => 'Bezdrátová sluchátka se stala standardem pro poslech hudby, telefonování a práci z domova. V tomto článku vám představíme nejlepší modely roku 2024 a pomůžeme vám vybrat ta správná.

## Kategorie sluchátek

### Over-ear sluchátka
Poskytují nejlepší kvalitu zvuku a pohodlí pro dlouhé poslechové sezení. Jsou ideální pro domácí použití.

### On-ear sluchátka
Kompaktnější varianta, stále s dobrým zvukem, ale méně pohodlná pro dlouhé nošení.

### In-ear sluchátka (TWS)
Nejkompaktnější varianta, ideální pro sport a cestování. Moderní modely nabízejí výbornou kvalitu zvuku.

## Nejlepší modely 2024

### Premium kategorie
- **Sony WH-1000XM5**: Vynikající potlačení hluku, skvělý zvuk
- **Apple AirPods Max**: Ideální pro uživatele Apple ekosystému
- **Bose QuietComfort 45**: Vynikající pohodlí a potlačení hluku

### Střední kategorie
- **Sony WH-CH720N**: Skvělý poměr cena/výkon
- **Jabra Elite 85h**: Vynikající pro telefonování
- **Sennheiser HD 450BT**: Skvělá kvalita zvuku

### Budget kategorie
- **Anker Soundcore Life Q30**: Skvělá hodnota za peníze
- **JBL Tune 760NC**: Solidní výkon za rozumnou cenu

## Na co se zaměřit při výběru

1. **Kvalita zvuku**: Frekvenční rozsah a přesnost reprodukce
2. **Potlačení hluku**: Aktivní noise cancelling (ANC)
3. **Výdrž baterie**: Minimálně 20 hodin pro over-ear modely
4. **Pohodlí**: Důležité pro dlouhé nošení
5. **Cena**: Rozpočtěte si, kolik chcete investovat

## Závěr

Výběr bezdrátových sluchátek závisí na vašich prioritách. Pro nejlepší zvuk a pohodlí zvolte premium modely, pro rozumný kompromis střední kategorii, a pokud máte omezený rozpočet, i budget modely mohou překvapit kvalitou.',
                'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800',
                'date' => '2024-01-01',
                'author' => 'Lucie Nováková',
            ],
            [
                'id' => 5,
                'title' => 'Gaming periferie: Co potřebujete pro skvělý herní zážitek',
                'excerpt' => 'Kompletní průvodce gaming periferiemi. Myši, klávesnice a další vybavení pro hráče.',
                'content' => 'Správné gaming periferie mohou výrazně zlepšit váš herní zážitek a výkon. V tomto průvodci vám představíme základní vybavení, které každý hráč potřebuje.

## Gaming myš

Gaming myš je základním nástrojem každého hráče. Hledejte:
- **Vysoké DPI**: Minimálně 800-1600 DPI, ideálně s možností úpravy
- **Rychlá odezva**: 1 ms odezva je standardem
- **Ergonomie**: Myš by měla dobře padnout do ruky
- **Programovatelné tlačítka**: Pro rychlé akce ve hrách

### Doporučené modely
- Logitech G Pro X Superlight
- Razer DeathAdder V3
- SteelSeries Rival 5

## Gaming klávesnice

Mechanická klávesnice je investice, která se vyplatí:
- **Mechanické spínače**: Různé typy pro různé preference (lineární, taktilní, klikací)
- **RGB osvětlení**: Nejen estetika, ale i praktičnost při hraní ve tmě
- **Anti-ghosting**: Důležité pro současné stisky více kláves

### Doporučené modely
- Corsair K70 RGB TKL
- Razer BlackWidow V4 Pro
- Keychron Q1 Pro

## Gaming headset

Kvalitní headset je klíčový pro komunikaci a zvuk:
- **7.1 surround zvuk**: Pro lepší orientaci ve hře
- **Kvalitní mikrofon**: Důležité pro týmovou komunikaci
- **Pohodlí**: Pro dlouhé herní sezení

### Doporučené modely
- SteelSeries Arctis 7P/7X
- HyperX Cloud Alpha
- Logitech G Pro X

## Gaming podložka

Podložka pod myš může zlepšit přesnost:
- **Velikost**: Větší je lepší pro nízkou citlivost myši
- **Povrch**: Hladký pro rychlé pohyby, hrubší pro kontrolu
- **Tloušťka**: 3-5 mm je ideální

## Další periferie

- **Gaming židle**: Pro pohodlí při dlouhém hraní
- **Monitor**: Vysoká obnovovací frekvence (144 Hz+) a nízká odezva
- **Kontrolér**: Pro konzolové hry nebo některé PC hry

## Závěr

Investice do kvalitních gaming periferií se vyplatí. Začněte s myší a klávesnicí, postupně přidávejte další vybavení podle svých potřeb a rozpočtu.',
                'image' => 'https://images.unsplash.com/photo-1527814050087-3793815479db?w=800',
                'date' => '2023-12-28',
                'author' => 'Tomáš Procházka',
            ],
            [
                'id' => 6,
                'title' => '4K monitory: Stojí za to investovat?',
                'excerpt' => 'Vše, co potřebujete vědět o 4K monitorech. Výhody, nevýhody a doporučení pro různé použití.',
                'content' => '4K monitory se stávají stále dostupnějšími, ale stojí za to investovat do nich? V tomto článku se podíváme na všechny aspekty 4K monitorů a pomůžeme vám rozhodnout.

## Co je 4K?

4K rozlišení znamená 3840 x 2160 pixelů, což je čtyřikrát více než Full HD (1920 x 1080). To znamená výrazně ostřejší obraz a více prostoru pro práci.

## Výhody 4K monitorů

### Ostřejší obraz
Výrazně ostřejší text a obrazy, zejména na větších monitorech (27" a více). To je výhodné pro práci s textem, grafikou a programováním.

### Více pracovního prostoru
Více pixelů znamená více místa pro otevření více oken vedle sebe bez zmenšování obsahu.

### Lepší pro grafiku a video
Pro práci s grafikou, videem a fotografiemi je 4K ideální. Vidíte více detailů a můžete pracovat přesněji.

### Budoucnost
4K se stává standardem, investice do 4K monitoru je investice do budoucnosti.

## Nevýhody 4K monitorů

### Vyšší cena
4K monitory jsou stále dražší než Full HD modely, i když rozdíl se zmenšuje.

### Nároky na hardware
Pro plynulé zobrazení 4K obsahu potřebujete výkonnější grafickou kartu, zejména pro hraní her.

### Menší text
Bez škálování může být text na 4K monitorech příliš malý. Moderní operační systémy však mají dobré škálování.

## Pro koho je 4K vhodné?

### Ideální pro:
- Grafické designéry a fotografy
- Video editory
- Programátory (více kódu na obrazovce)
- Profesionální práci s textem
- Hráče s výkonným hardwarem

### Možná zbytečné pro:
- Běžné kancelářské práce
- Hráče s omezeným rozpočtem na hardware
- Uživatele s menšími monitory (pod 27")

## Doporučení

### Pro práci
Pokud pracujete s grafikou, videem nebo programováním, 4K monitor je skvělá investice. Pro běžnou kancelářskou práci může být Full HD dostačující.

### Pro hraní
4K gaming vyžaduje výkonnou grafickou kartu (RTX 3070 nebo lepší). Pokud máte omezený rozpočet, zvažte 1440p (2K) jako kompromis.

### Velikost monitoru
4K má největší smysl na monitorech 27" a větších. Na menších monitorech může být rozdíl méně patrný.

## Závěr

4K monitory stojí za investici, pokud:
- Pracujete s grafikou, videem nebo programováním
- Máte výkonný hardware
- Používáte monitor 27" nebo větší
- Chcete investovat do budoucnosti

Pro běžné použití může být Full HD nebo 1440p dostačující a úspornější volbou.',
                'image' => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=800',
                'date' => '2023-12-25',
                'author' => 'Anna Kratochvílová',
            ],
            [
                'id' => 7,
                'title' => 'Jak správně čistit a udržovat elektroniku',
                'excerpt' => 'Praktické tipy pro údržbu vašich elektronických zařízení. Prodloužte životnost vašich přístrojů.',
                'content' => 'Správná údržba elektroniky může výrazně prodloužit její životnost a zachovat výkon. V tomto článku vám ukážeme, jak správně čistit a udržovat vaše zařízení.

## Obecné zásady

### Vypněte zařízení
Před jakoukoli údržbou vždy vypněte zařízení a odpojte ho od napájení. U notebooků také vyjměte baterii, pokud je to možné.

### Používejte správné nástroje
- Měkké hadříky z mikrovlákna
- Stlačený vzduch pro odstranění prachu
- Izopropylalkohol pro dezinfekci
- Měkké štětce pro jemné čištění

## Notebooky a počítače

### Klávesnice
Klávesnici čistěte pravidelně pomocí stlačeného vzduchu a měkkého štětce. Pro důkladné čištění můžete použít izopropylalkohol na hadřík.

### Displej
Čistěte pouze měkkým hadříkem z mikrovlákna, ideálně navlhčeným destilovanou vodou. Nikdy nepoužívejte agresivní čisticí prostředky.

### Vnitřní části
Pro čištění ventilátorů a vnitřních částí použijte stlačený vzduch. Dělejte to pravidelně, aby se předešlo přehřívání.

## Smartphony a tablety

### Displej
Čistěte pravidelně měkkým hadříkem. Pro dezinfekci použijte hadřík navlhčený izopropylalkoholem (70%), ale nikdy přímo na zařízení.

### Porty a konektory
Porty čistěte opatrně pomocí měkkého štětce nebo stlačeného vzduchu. Nikdy nepoužívejte kovové předměty.

### Kryt
Kryt můžete čistit mýdlem a vodou, pokud není elektronický. Ujistěte se, že je zařízení vypnuté a kryt je suchý před nasazením.

## Sluchátka

### Ušní polštářky
Ušní polštářky lze často vyjmout a vyprat. Zkontrolujte návod výrobce.

### Sluchátka
Čistěte měkkým hadříkem navlhčeným izopropylalkoholem. Dávejte pozor, aby se tekutina nedostala dovnitř.

## Pravidelná údržba

### Týdenní údržba
- Otření displejů a obrazovek
- Odstranění prachu z klávesnic
- Kontrola konektorů

### Měsíční údržba
- Důkladné čištění klávesnice
- Čištění vnitřních částí počítače
- Kontrola a čištění portů

### Roční údržba
- Kompletní servisní kontrola
- Výměna tepelné pasty u notebooků (pokud je potřeba)
- Kontrola baterie

## Čeho se vyvarovat

- Nikdy nepoužívejte agresivní čisticí prostředky
- Nepoužívejte vodu přímo na elektroniku
- Nepoužívejte kovové předměty pro čištění
- Nečistěte zařízení, když je zapnuté
- Nepoužívejte vysavač přímo na elektroniku

## Závěr

Pravidelná údržba vašich elektronických zařízení je investice, která se vyplatí. Správnou péčí můžete výrazně prodloužit životnost vašich přístrojů a zachovat jejich výkon.',
                'image' => 'https://images.unsplash.com/photo-1587825140708-dfaf72ae4b04?w=800',
                'date' => '2023-12-20',
                'author' => 'Jan Novák',
            ],
            [
                'id' => 8,
                'title' => 'Cloudové úložiště: Která služba je nejlepší?',
                'excerpt' => 'Porovnání nejpopulárnějších cloudových úložišť. Najděte ideální řešení pro vaše potřeby.',
                'content' => 'Cloudové úložiště se stalo nezbytností pro moderní práci a osobní použití. V tomto článku porovnáme nejpopulárnější služby a pomůžeme vám vybrat tu správnou.

## Hlavní poskytovatelé

### Google Drive
**Výhody:**
- Integrace s Google službami
- 15 GB zdarma
- Skvělá spolupráce na dokumentech
- Dostupné na všech platformách

**Nevýhody:**
- Omezené soukromí
- Závislost na Google ekosystému

**Cena:** Od 79 Kč/měsíc za 100 GB

### Dropbox
**Výhody:**
- Jednoduché použití
- Rychlá synchronizace
- Skvělá spolupráce
- Dobrá integrace s aplikacemi

**Nevýhody:**
- Pouze 2 GB zdarma
- Dražší než konkurence

**Cena:** Od 249 Kč/měsíc za 2 TB

### OneDrive
**Výhody:**
- Integrace s Microsoft 365
- 5 GB zdarma
- Skvělá pro Office dokumenty
- Dostupné na všech platformách

**Nevýhody:**
- Méně funkcí než konkurence
- Omezená podpora Linuxu

**Cena:** Od 149 Kč/měsíc za 100 GB (součást Microsoft 365)

### iCloud
**Výhody:**
- Perfektní integrace s Apple zařízeními
- Automatická synchronizace
- 5 GB zdarma

**Nevýhody:**
- Omezená podpora Windows/Android
- Dražší než konkurence

**Cena:** Od 29 Kč/měsíc za 50 GB

### MEGA
**Výhody:**
- 20 GB zdarma
- End-to-end šifrování
- Dobré soukromí

**Nevýhody:**
- Méně známá služba
- Omezená integrace

**Cena:** Od 199 Kč/měsíc za 400 GB

## Porovnání podle použití

### Pro osobní použití
**Doporučení:** Google Drive nebo iCloud (pro uživatele Apple)

### Pro práci
**Doporučení:** Dropbox nebo OneDrive (s Microsoft 365)

### Pro soukromí
**Doporučení:** MEGA nebo vlastní řešení (Nextcloud)

### Pro spolupráci
**Doporučení:** Google Drive nebo Dropbox

## Bezpečnost a soukromí

Všechny hlavní služby používají šifrování, ale:
- **Google Drive, OneDrive, iCloud**: Šifrování na straně serveru
- **Dropbox**: Šifrování při přenosu i na serveru
- **MEGA**: End-to-end šifrování

## Tipy pro výběr

1. **Zvažte své potřeby**: Kolik úložiště skutečně potřebujete?
2. **Zkontrolujte integraci**: Používáte často konkrétní ekosystém?
3. **Zamyslete se nad soukromím**: Jak důležité je pro vás soukromí?
4. **Porovnejte ceny**: Ceny se liší, najděte nejlepší poměr cena/výkon
5. **Vyzkoušejte zdarma**: Většina služeb má bezplatnou verzi

## Závěr

Nejlepší cloudové úložiště závisí na vašich konkrétních potřebách. Pro většinu uživatelů je Google Drive nebo Dropbox dobrou volbou díky jednoduchosti a funkcím. Pro uživatele Apple je iCloud přirozenou volbou, pro práci s Office dokumenty OneDrive.',
                'image' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=800',
                'date' => '2023-12-15',
                'author' => 'Marie Svobodová',
            ],
            [
                'id' => 9,
                'title' => 'Zabezpečení domácí Wi-Fi sítě',
                'excerpt' => 'Jak zabezpečit svou Wi-Fi síť a ochránit svá data. Praktické tipy pro bezpečnost.',
                'content' => 'Zabezpečení domácí Wi-Fi sítě je klíčové pro ochranu vašich dat a zařízení. V tomto článku vám ukážeme, jak správně zabezpečit svou síť.

## Základní kroky zabezpečení

### 1. Změňte výchozí heslo správce
Všechny routery mají výchozí přihlašovací údaje. První krok je změnit heslo správce routeru na silné a jedinečné heslo.

### 2. Použijte WPA3 nebo WPA2
Ujistěte se, že vaše Wi-Fi síť používá WPA3 (nejnovější) nebo alespoň WPA2. Nikdy nepoužívejte WEP, který je zastaralý a nebezpečný.

### 3. Silné heslo Wi-Fi
Vytvořte silné heslo pro Wi-Fi síť:
- Minimálně 12 znaků
- Kombinace velkých a malých písmen, čísel a symbolů
- Nepoužívejte osobní informace

### 4. Změňte SSID (název sítě)
Změňte výchozí název sítě. Nepoužívejte osobní informace v názvu, ale také ne název, který příliš prozrazuje typ routeru.

### 5. Vypněte WPS
WPS (Wi-Fi Protected Setup) může být zranitelný. Pokud ho nepoužíváte, vypněte ho v nastavení routeru.

## Pokročilé zabezpečení

### Firewall
Ujistěte se, že firewall na routeru je zapnutý. Většina moderních routerů má firewall aktivní ve výchozím nastavení.

### Aktualizace firmware
Pravidelně aktualizujte firmware routeru. Výrobci vydávají aktualizace pro opravu bezpečnostních chyb.

### Vypněte vzdálenou správu
Pokud nepotřebujete přistupovat k routeru z internetu, vypněte vzdálenou správu. To omezí možnost útoku zvenčí.

### MAC filtrování (volitelné)
Můžete povolit připojení pouze pro konkrétní zařízení pomocí jejich MAC adres. To však může být nepraktické, pokud často přidáváte nová zařízení.

### Guest síť
Vytvořte samostatnou guest síť pro návštěvy. Tato síť by měla být izolovaná od vaší hlavní sítě.

## Pravidelná údržba

### Kontrola připojených zařízení
Pravidelně kontrolujte, která zařízení jsou připojena k vaší síti. Pokud najdete neznámé zařízení, okamžitě změňte heslo.

### Monitorování provozu
Některé routery umožňují monitorovat síťový provoz. Sledujte neobvyklou aktivitu.

### Zálohování nastavení
Pravidelně zálohujte nastavení routeru. To usnadní obnovu v případě problému.

## Časté chyby

### Slabé heslo
Nejčastější chyba je použití slabého hesla. Investujte čas do vytvoření silného hesla.

### Sdílení hesla
Nesdílejte heslo Wi-Fi s každým. Pokud někdo potřebuje přístup, použijte guest síť.

### Ignorování aktualizací
Aktualizace firmware často opravují bezpečnostní chyby. Neignorujte je.

### Výchozí nastavení
Nikdy nenechávejte router ve výchozím nastavení. Vždy změňte hesla a základní nastavení.

## Závěr

Zabezpečení Wi-Fi sítě není složité, ale vyžaduje pozornost. Začněte základními kroky - změnou hesel a použitím WPA2/WPA3. Postupně přidávejte pokročilejší opatření podle svých potřeb. Pravidelná kontrola a údržba zajistí, že vaše síť zůstane bezpečná.',
                'image' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800',
                'date' => '2023-12-10',
                'author' => 'Petr Dvořák',
            ],
            [
                'id' => 10,
                'title' => 'Chytré domácí spotřebiče: Začínáme',
                'excerpt' => 'Úvod do světa chytrých domácích spotřebičů. Jak začít a co potřebujete vědět.',
                'content' => 'Chytré domácí spotřebiče mohou výrazně zjednodušit váš život a zvýšit komfort bydlení. V tomto článku vám ukážeme, jak začít s chytrou domácností.

## Co jsou chytré spotřebiče?

Chytré spotřebiče jsou zařízení, která lze ovládat na dálku pomocí smartphonu nebo hlasových příkazů. Mohou se také automaticky přizpůsobovat vašim potřebám a šetřit energii.

## Základní vybavení

### Chytrý hub
Centrální zařízení, které propojuje všechny chytré spotřebiče. Populární jsou:
- Amazon Echo (Alexa)
- Google Nest Hub
- Apple HomePod
- Samsung SmartThings Hub

### Chytré žárovky
Nejjednodušší způsob, jak začít. Můžete ovládat osvětlení, měnit barvy a nastavovat časovače.

### Chytré zásuvky
Přemění běžné spotřebiče na chytré. Můžete je zapínat a vypínat na dálku a sledovat spotřebu energie.

### Chytrý termostat
Automaticky reguluje teplotu v domě, šetří energii a zvyšuje komfort.

## Oblíbené kategorie

### Osvětlení
- Chytré žárovky (Philips Hue, LIFX)
- Chytré pásky LED
- Chytré přepínače

### Bezpečnost
- Chytré zámky
- Bezpečnostní kamery
- Chytré alarmy
- Senzory pohybu

### Klimatizace a topení
- Chytré termostaty (Nest, Ecobee)
- Chytré ventilátory
- Chytré klimatizace

### Čištění
- Robotické vysavače (Roomba, Roborock)
- Chytré pračky a sušičky

### Kuchyně
- Chytré ledničky
- Chytré trouby
- Chytré kávovary

## Jak začít

### 1. Začněte malé
Nekupujte všechno najednou. Začněte s jedním nebo dvěma zařízeními, například chytrými žárovkami.

### 2. Zvolte ekosystém
Rozhodněte se pro jeden ekosystém (Amazon Alexa, Google Assistant nebo Apple HomeKit) a držte se ho pro lepší kompatibilitu.

### 3. Zkontrolujte kompatibilitu
Před nákupem zkontrolujte, zda jsou zařízení kompatibilní s vaším hubem a ekosystémem.

### 4. Postupně rozšiřujte
Jak se seznámíte s chytrými spotřebiči, postupně přidávejte další zařízení podle svých potřeb.

## Výhody

### Pohodlí
Ovládání spotřebičů na dálku nebo hlasem šetří čas a zvyšuje komfort.

### Úspora energie
Automatické vypínání a optimalizace spotřeby může ušetřit peníze.

### Bezpečnost
Chytré bezpečnostní systémy mohou zvýšit bezpečnost vašeho domova.

### Automatizace
Můžete vytvářet scénáře, například "dobré ráno", které zapne světla, kávovar a hudbu.

## Nevýhody

### Cena
Chytré spotřebiče jsou dražší než běžné modely.

### Závislost na internetu
Většina zařízení vyžaduje internetové připojení.

### Soukromí
Některá zařízení mohou shromažďovat data o vašem chování.

### Kompatibilita
Ne všechna zařízení jsou vzájemně kompatibilní.

## Tipy pro začátečníky

1. **Začněte s osvětlením**: Nejsnadnější způsob, jak začít
2. **Investujte do kvalitního hubu**: To je základ celého systému
3. **Čtěte recenze**: Před nákupem si přečtěte recenze a porovnání
4. **Začněte s automatizacemi**: Vytvářejte jednoduché scénáře
5. **Buďte trpěliví**: Nastavení může chvíli trvat

## Závěr

Chytré domácí spotřebiče mohou výrazně zlepšit váš životní styl. Začněte malé s osvětlením nebo chytrými zásuvkami a postupně rozšiřujte podle svých potřeb. Investice do kvalitního hubu a ekosystému se vyplatí pro dlouhodobé použití.',
                'image' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800',
                'date' => '2023-12-05',
                'author' => 'Lucie Nováková',
            ],
        ];

        // Přidání slug ke každému článku
        foreach ($articles as &$article) {
            $article['slug'] = Str::slug($article['title']);
        }

        return $articles;
    }

    /**
     * Zobrazení seznamu článků s paginací
     */
    public function index(Request $request)
    {
        $allArticles = self::getAllArticles();

        // Počet článků na stránku
        $perPage = 6;
        $currentPage = $request->get('page', 1);

        // Vytvoření paginace
        $total = count($allArticles);
        $articles = array_slice($allArticles, ($currentPage - 1) * $perPage, $perPage);

        // Vytvoření paginace objektu podobného Laravel pagination
        $pagination = [
            'current_page' => (int) $currentPage,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => (int) ceil($total / $perPage),
            'from' => ($currentPage - 1) * $perPage + 1,
            'to' => min($currentPage * $perPage, $total),
        ];

        return Inertia::render('Article/index', [
            'articles' => $articles,
            'pagination' => $pagination,
        ]);
    }

    /**
     * Zobrazení detailu článku podle slug
     */
    public function show($slug)
    {
        $allArticles = self::getAllArticles();

        // Najít článek podle slug
        $article = null;
        foreach ($allArticles as $item) {
            if ($item['slug'] === $slug) {
                $article = $item;
                break;
            }
        }

        // Pokud článek neexistuje, vrátíme 404
        if (!$article) {
            abort(404, 'Článek nenalezen');
        }

        // Najít související články (poslední 3 kromě aktuálního)
        $relatedArticles = array_filter($allArticles, function($item) use ($article) {
            return $item['id'] !== $article['id'];
        });
        $relatedArticles = array_slice(array_values($relatedArticles), 0, 3);

        return Inertia::render('Article/show', [
            'article' => $article,
            'relatedArticles' => $relatedArticles,
        ]);
    }
}

