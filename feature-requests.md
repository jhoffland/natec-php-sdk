# Requests

- [ ] Instead of using an empty string, "No {key}", 0 or "0001-01-01", using `null` as value.
    * Recognizing the placeholder "No {key}" is error prone (for example: <em>No Otype</em>).
    * Recognizing the unavailability of a value from the value 0 is error prone (an example is the field `wattpiek` of products).
- [ ] Returning numbers as a number. Examples of fields where this is not the case:
  - [x] `grossPrice`, `netPrice` en `discount` voor product prices.
  - [x] `iscA`, `vocV`, `impA`, `vmpV` en `pmaxW` voor flash data.
- [ ] On successfully placing an order, returning the order number or the sufficient order. In case the sufficient order is return, it would be convenient that the response body matches the response body of `GET /orders`. 
- [ ] If available, the `updatedAt` field of assortment updates also containg the time.

# Needed data

The overviews below show the minimal needed details per entity. A lot of these details are already available and some can be obtained from other resources. 

A really handy feature would be the ability to filter resources on their `updated_at` timestamp; retrieve the resources with `updated_at` greater than the greatest `updated_at` timestamp of the last set of resources retrieved.<br />
This also means that the subresources need separate endpoints with this filter functionality. For example, `GET /orders/lines` for order lines would be more efficient than `GET /orders/{order_number}/lines`.<br />
When this filter is used properly the API responses only contain the resources needed.

In the overviews:
* All the resources have a `updated_at` and some a `created_at` field. The values of these field has to contain a date and time.
* All the resources contain an ID; a unique identifier that never changes. These ID's add the most value when the value match the value got from the ERP system. 
* Some fields are between brackets. These values are only needed when available.

All the prices return by the API have to be excluding VAT and in Euros, unless specified otherwise by the API.

## Units

```text
* (ID)
* Code
* Description
* Amount of pieces --> currently this is called vpe and available through prices of products
```

## ProductCategories/Groups

```text
* (ID)
* Code
* Description
* `updated_at`
```

## Products

```text
* (ID)
* Product number 
* Description
* ProductGroup/Category ID/Code --> reference to the ProductGroup/Category resource
* Product number from the manufacturer
* (Start date) --> available since
* (End date) --> available until
* `updated_at`
```

## ProductPrices

```text
* Product ID/number --> reference to the product resource
* Unit ID/code --> reference to the unit resource
* Net price per unit
* (Net price per piece) --> can be calculated
* (Gross price per unit)
* (Discount)
* Start date --> price valid from
* End date --> price valid until
* `updated_at`
```

## Orders

```text
* (ID)
* Order number
* Order date --> the posting date of the order 
* (Description) 
* Order placed by --> person who placed the order, via the klantportaal or by phone/email
* Order lines
  * ID
  * Order ID/number --> reference to the parent (order) resource
  * Order line number
  * Product ID/number --> reference to the product resource
  * Unit ID/code  --> reference to the unit resource
  * Unit quantity
  * (Pieces quantity) --> can be calculated
  * Expected shipment date
  * Shipping time --> the time it will take to deliver the product
  * (Expected delivery date)
  * Net line amount excluding VAT
  * (Description)
  * `updated_at`
* Order confirmation PDF
* `updated_at`
```

## Shipments

```text
* (ID)
* Shipment number
* (Packing slip number) --> if it deviates from the shipment number
* Shipping date
* (Delivery date)
* (Description)
* Shipment lines
  * ID
  * Shipment ID/number --> reference to the parent (shipment) resource
  * Order line ID --> reference to the order line resource
  * Shipment line number 
  * Unit ID/code --> reference to the unit resource
  * Shipped unit quantity
  * (Shipped pieces quantity) --> can be calculated
  * Serial numbers
  * `created_at`
  * `updated_at`
* Packing slip PDF
* `created_at`
* `updated_at`
```

## Returns

It has been assumed that:
* A return is a separate entity. For example, a return could also be shipment with negatieve quantities.
* A return is connected to a shipment (the shipment returned).

```text
* (ID)
* Return number
* Return date
* (Description)
* Return lines
  * ID
  * Return ID/number --> reference to the parent (return) resource
  * Shipment line ID --> reference to the shipment line resource
  * (Return line number)
  * Unit ID/code --> reference to the unit resource
  * Returned unit quantity
  * (Return pieces quantity) --> can be calculated
  * Serial numbers
  * `created_at`
  * `updated_at`
* (PDF)
* `created_at`
* `updated_at`
```

## Invoices

```text
* (ID)
* Invoice number
* (Description)
* Invoice date
* Expiration date
* Invoice lines
  * ID
  * Invoice ID/number --> reference to the parent (invoice) resource
  * Order line ID --> reference to the order line resource
  * Invoice line number
  * Unit ID/code --> reference to the unit resource
  * Invoiced unit quantity
  * (Invoiced pieces quantity) --> can be calculated
  * Net line amount excluding VAT
  * (Description)
  * `created_at`
  * `updated_at`
* Invoice PDF
* `created_at`
* `updated_at`
```
