# Natec Klantportaal API Client Library for PHP


[![Run tests](https://github.com/jhoffland/natec-php-sdk/actions/workflows/testing.yml/badge.svg)](https://github.com/jhoffland/natec-php-sdk/actions/workflows/testing.yml)
[![codecov](https://codecov.io/gh/jhoffland/natec-php-sdk/branch/main/graph/badge.svg?token=L2QKKTY5G4)](https://codecov.io/gh/jhoffland/natec-php-sdk)

## Usage

```php
use NatecSdk\Client;
use NatecSdk\Resources\AssortmentUpdate;
use NatecSdk\Resources\Invoice;
use NatecSdk\Resources\Shipment;

$apiToken = 'xxx';
$client = new Client($apiToken);

// Get an iterator with all resources, matching the filter (query).
$assortmentUpdateIterator = AssortmentUpdate::get($client, [
    'type' => 'product_expected_arrival'
]);

foreach($assortmentUpdateIterator as $assortmentUpdate) {
    var_dump($assortmentUpdate);
}

// Get all resources retrieved by the iterator.
var_dump($assortmentUpdateIterator->allRetrieved());

// Get one resource, by the primary key value (id). The primary key for e.g. invoices is documentNo.
var_dump(Invoice::find($client, 'GVFN22-12345'));

// Make an API request
var_dump($client->get(Shipment::endpoint()));
var_dump($client->post('/orders', [ 'reference' => 'Example order' ]));
```


## Contributing

Feel free to contribute to this library. Contribute by forking the GitHub repository and opening a pull request.
When opening a pull request, please make sure that:

* The pull request has a clear title;
* The pull request does not consist of too many (unnecessary/small) commits;
* Tests are added or updated to test the added/improved functionality;
* The code complies to the coding standard, passes the code analysis and passes all the tests (`composer run-tests`):
  * PHP_CodeSniffer (`vendor/bin/phpcs`);
  * PHPStan (`vendor/bin/phpstan`);
  * PHPUnit (`vendor/bin/phpunit`).


## ToDo's

- [ ] Add a test for every resource.
- [ ] Give every assertion in the tests a descriptive message.


## Feature requests


Wat is de tijdzone van de API?


1. Als een gegeven niet beschikbaar is i.p.v. een lege string, "No {key}", 0 of "0001-01-01", `null` als waarde wordt gebruikt. 
   * Het herkennen van de placeholder "No {key}" is lastig en foutgevoelig (bijvoorbeeld: <em>No Otype</em>).
   * Het herkennen dat een waarde niet beschikbaar is aan de standaard waarde 0 is foutgevoelig (bijvoorbeeld voor het veld `wattpiek` van products).
   * Het herkennen van "0001-01-01" als er geen datum beschikbaar is, is foutgevoelig (bijvoorbeeld voor het veld `promised_delivery_date` van orders).
2. Getallen als getal mee worden gegeven. Voorbeelden van waar dit nu niet het geval is:
   * `grossPrice`, `netPrice` en `discount` voor product prices.
   * `iscA`, `vocV`, `impA`, `vmpV` en `pmaxW` voor flash data.
3. De gegevens en query parameters t.b.v. paginering voor elk endpoint hetzelfde zijn.
4. Bij het succesvol plaatsen van de order, het ordernummer of de volledige order wordt teruggeven. Indien de volledige order wordt teruggegeven, zou het prettig werken, als de structuur overeenkomt met de structuur van `GET /orders`.
5. Indien beschikbaar, de `updatedAt` van assortment updates ook de tijd bevat (op de (milli)seconde).

### Benodigde gegevens

In de onderstaande overzichten staan per resource de minimaal benodigde gegevens. Bij het samenstellen van deze overzichten zijn bepaalde aannames gedaan, die er ook bij zijn vermeld.

Een enorm handige functionaliteit zou de mogelijkheid zijn om per resource (endpoint) te kunnen filteren op bijgewerkt vanaf (o.b.v. het `updated_at` veld).<br />
Bepaalde resources bevatten "subresources" (bijvoorbeeld: factuurregels, orderregels, etc.) die bijgewerkt zouden kunnen worden, zonder dat de bovenliggende resource wordt bijgewerkt. Het is belangrijk dat een resource ook het resultaat is van het filter, als alleen een "subresource" is bijgewerkt.<br />
Het werkt prettig als de resources (bij het filteren op bijgewerkt vanaf) in oplopende volgorde zijn gesorteerd.

In de onderstaande overzichten:
* Zijn geen unieke ID's opgenomen. Dit zou wel enorm handig zijn en bij voorkeur voor elke entiteit beschikbaar worden gemaakt en gebruikt worden voor een directe referentie naar andere entiteiten (bijvoorbeeld een verwijzing naar een order d.m.v. order_id i.p.v. ordernummer).
* Hebben alle resources een Bijgewerkt op (`updated_at`) en sommigen een Gemaakt op (`created_at`) veld. Dit veld bevat zowel de datum als tijd (op de (milli)seconde).

#### Products

```text
* Artikelnummer
* Omschrijving
* Productgroep/categorie
  * Code
  * Omschrijving
* Artikelnummer fabrikant
* (Startdatum) --> beschikbaar sinds. Indien beschikbaar
* (Einddatum) --> beschikbaar tot. Indien beschikbaar
* Bijgewerkt op (`updated_at`)
```

#### Orders

```text
* Ordernummer
* Orderdatum
* (Omschrijving) --> indien beschikbaar
* Order geplaatst door (`sell_to_contact`?)
* Orderregels
  * Orderregelnummer
  * Artikelnummer
  * Aantal besteld in regeleenheid
  * Regeleenheid
    * Code
    * Omschrijving
    * Aantal stuks per eenheid
  * (Aantal bestelde stuks) --> kan uitgerekend worden
  * Verwachtte verzenddatum
  * (Verwachtte afleverdatum) --> indien beschikbaar, anders moet deze "uitgerekend" gaan worden a.d.v. de verwachte verzenddatum.
  * Totaalbedrag orderregel excl. BTW
  * (Orderrregelomschrijving) --> indien beschikbaar en deze af kan wijken van de artikelomschrijving
  * Bijgewerkt op (`updated_at`)
* Orderbevestiging PDF
* Bijgewerkt op (`updated_at`)
```

#### Shipments

In de onderstaande lijst wordt als vertaling van shipment levering gebruikt.

```text
* Levernummer
* (Pakbonnummer) --> indien deze afwijkt van het levernummer
* Ordernummer
* Verzenddatum
* (Afleverdatum) --> indien beschikbaar
* (Omschrijving) --> indien beschikbaar
* Leveringsregels
  * Leveringsregelnummer
  * Orderregelnummer
  * (Artikelnummer) --> indien beschikbaar; kan opgehaald worden via orderregel
  * Aantal geleverd/verzonden in regeleenheid
  * Regeleenheid
    * Code
    * Omschrijving
    * Aantal stuks per eenheid
  * (Aantal geleverde/verzonden stuks) --> kan uitgerekend worden
  * Serienummers
  * Gemaakt op (`created_at`)
  * Bijgewerkt op (`updated_at`)
* Pakbon PDF
* Gemaakt op (`created_at`)
* Bijgewerkt op (`updated_at`)
```

#### Returns

Er is vanuit gegaan dat:
* Een retour een aparte entiteit is. Een retour zou bijvoorbeeld ook een levering kunnen zijn met negatieve aantallen.
* Een retour gekoppeld is aan een levering.

```text
* Retournummer
* Levernummer --> referentie naar een levering kan ook op regel niveau, hierdoor kan een retour uit producten van verschillende leveringen bestaan.
* Retourdatum
* (Omschrijving) --> indien beschikbaar
* Retourregels
  * Retourregelnummer
  * Leveringsregelnummer
  * (Artikelnummer) --> indien beschikbaar; kan opgehaald worden via leveringsregel.
  * Aantal geretourneerd in regeleenheid
  * Regeleenheid
    * Code
    * Omschrijving
    * Aantal stuks per eenheid
  * (Aantal geretourneerde stuks) --> kan uitgerekend worden
  * Serienummers
  * Gemaakt op (`created_at`)
  * Bijgewerkt op (`updated_at`)
* (PDF) --> indien beschikbaar voor retouren
* Gemaakt op (`created_at`)
* Bijgewerkt op (`updated_at`)
```

#### Invoices

Er is vanuit gegaan dat:
* Een factuur geen koppeling heeft met een levering of retour. Als dit wel het geval is, worden de referenties naar de order en orderregels vervangen voor referenties naar levering-/retourregels.

```text
* Factuurnummer
* Ordernummer
* (Factuuromschrijving)
* Factuurdatum
* Vervaldatum
* Factuurregels
  * (Factuurregelnummer) --> indien deze waarde ook echt bestaat
  * Orderregelnummer
  * (Artikelnummer) --> indien beschikbaar; kan opgehaald worden via orderregel
  * Aantal gefactureerde in regeleenheid
  * Regeleenheid
    * Code
    * Omschrijving
    * Aantal stuks per eenheid
  * (Aantal gefactureerde stuks) --> kan uitgerekend worden
  * Totaalbedrag factuurregel excl. BTW
  * (Factuurregelomschrijving) --> indien beschikbaar en deze af kan wijken van de artikelomschrijving/orderregelomschrijving
  * Gemaakt op (`created_at`)
  * Bijgewerkt op (`updated_at`)
* Factuur PDF
* Gemaakt op (`created_at`)
* Bijgewerkt op (`updated_at`)
```

#### ProductPrices

```text
* Artikelnummer
* Eenheid
  * Code
  * Omschrijving
  * Aantal stuks per eenheid --> verpakkingseenheid?
* Netto inkoopprijs per eenheid
* (Netto inkoopprijs per stuk) --> kan uitgerekend worden
* (Bruto inkoopprijs per eenheid)
* (Korting)
* Startdatum --> prijs geldig vanaf
* Einddatum --> prijs geldig tot (en met)
* Bijgewerkt op (`updated_at`)
```
