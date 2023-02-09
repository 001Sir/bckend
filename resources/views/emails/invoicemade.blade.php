<x-mail::message>
# Invoice Created

Your invoice has been created.

## Package Name
{{ $package }}

### Price
{{ $price }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
