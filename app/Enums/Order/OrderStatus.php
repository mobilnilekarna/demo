<?php

namespace App\Enums\Order;

use App\Enums\Order\Status;

enum Status : string {
    case CREATED = 'created';     // Vytvořeno
    case ACCEPTED = 'accepted';   // Akceptováno
    case PROCESSING = 'processing'; // Připravuje se
    case SHIPPED = 'shipped';     // Odesláno
    case PICKUP = 'pickup';       // K vyzvednutí
    case COMPLETED = 'completed'; // Dokončeno
    case CANCELED = 'canceled';   // Zrušeno

    public function name(): string
    {
        return match($this) {
            Status::CREATED => __('general.order.status.created'),
            Status::ACCEPTED => __('general.order.status.accepted'),
            Status::PROCESSING => __('general.order.status.processing'),
            Status::SHIPPED => __('general.order.status.shipped'),
            Status::PICKUP => __('general.order.status.pickup'),
            Status::COMPLETED => __('general.order.status.completed'),
            Status::CANCELED => __('general.order.status.canceled'),
        };
    }

    public function color(): string
    {
        return match($this) {
            Status::CREATED => "bg-gray-100 text-gray-800",
            Status::ACCEPTED => "bg-blue-100 text-blue-800",
            Status::PROCESSING => "bg-yellow-100 text-yellow-800",
            Status::SHIPPED => "bg-green-100 text-green-800",
            Status::PICKUP => "bg-purple-100 text-purple-800",
            Status::COMPLETED => "bg-green-100 text-green-800",
            Status::CANCELED => "bg-gray-100 text-gray-800",
        };
    }

    public function icon(): string
    {
        return match($this) {
            Status::CREATED => "fa-solid fa-clock",
            Status::ACCEPTED => "fa-solid fa-check",
            Status::PROCESSING => "fa-solid fa-clock",
            Status::SHIPPED => "fa-solid fa-truck",
            Status::PICKUP => "fa-solid fa-box",
            Status::COMPLETED => "fa-solid fa-check",
            Status::CANCELED => "fa-solid fa-times",
        };
    }

    public function badge(): string
    {
        return match($this) {
            Status::CREATED => "bg-gray-100 text-gray-800",
            Status::ACCEPTED => "bg-blue-100 text-blue-800",
            Status::PROCESSING => "bg-yellow-100 text-yellow-800",
            Status::SHIPPED => "bg-green-100 text-green-800",
            Status::PICKUP => "bg-purple-100 text-purple-800",
            Status::COMPLETED => "bg-green-100 text-green-800",
            Status::CANCELED => "bg-gray-100 text-gray-800",
        };
    }

    public function label(): string
    {
        return match($this) {
            Status::CREATED => __('general.order.status.created'),
            Status::ACCEPTED => __('general.order.status.accepted'),
            Status::PROCESSING => __('general.order.status.processing'),
            Status::SHIPPED => __('general.order.status.shipped'),
            Status::PICKUP => __('general.order.status.pickup'),
            Status::COMPLETED => __('general.order.status.completed'),
            Status::CANCELED => __('general.order.status.canceled'),
        };
    }

    public function description(): string
    {
        return match($this) {
            Status::CREATED => __('general.order.status.created'),
            Status::ACCEPTED => __('general.order.status.accepted'),
            Status::PROCESSING => __('general.order.status.processing'),
            Status::SHIPPED => __('general.order.status.shipped'),
            Status::PICKUP => __('general.order.status.pickup'),
            Status::COMPLETED => __('general.order.status.completed'),
            Status::CANCELED => __('general.order.status.canceled'),
        };
    }

    public function template(): string
    {
        return match($this) {
            Status::CREATED => "emails.order.created",
            Status::ACCEPTED => "emails.order.accepted",
            Status::PROCESSING => "emails.order.processing",
            Status::SHIPPED => "emails.order.shipped",
            Status::PICKUP => "emails.order.pickup",
            Status::COMPLETED => "emails.order.completed",
            Status::CANCELED => "emails.order.canceled",
        };
    }

    public function isChangeable(): bool
    {
        return match($this) {
            Status::CREATED => [Status::ACCEPTED, Status::CANCELED],
            Status::ACCEPTED => [Status::PROCESSING, Status::CANCELED],
            Status::PROCESSING => [Status::SHIPPED, Status::PICKUP, Status::CANCELED],
            Status::SHIPPED => [Status::READY, Status::CANCELED],
            Status::PICKUP => [Status::READY, Status::CANCELED],
            Status::COMPLETED => [],
            Status::CANCELED => [],
        };
    }
}
