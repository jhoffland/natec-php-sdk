<?php declare(strict_types=1);

namespace NatecSdk\Resources\Types;

enum InvoiceType
{
    case REGULAR;
    case PREPAYMENT;
    case CREDIT;

    public static function fromInvoiceNo(string $documentNo): ?InvoiceType
    {
        if (preg_match('/^(GVFN)([1-9]{2})-([0-9]{1,})$/', $documentNo) === 1) {
            return InvoiceType::REGULAR;
        }

        if (preg_match('/^(GCNV|GVC)([1-9]{2})-([0-9]{1,})$/', $documentNo) === 1) {
            return InvoiceType::CREDIT;
        }

        if (preg_match('/^(GVBF)([1-9]{2})-([0-9]{1,})$/', $documentNo) === 1) {
            return InvoiceType::PREPAYMENT;
        }

        return null;
    }
}
