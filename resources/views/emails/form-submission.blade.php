<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $isConfirmation ? 'Submission received' : 'New form submission' }}</title>
</head>
<body>
    <h1>{{ $isConfirmation ? 'Thank you for contacting us' : 'New '.$formName }}</h1>
    <p>
        @if ($isConfirmation)
            We received your {{ $formName }} and will be in touch soon.
        @else
            A new {{ $formName }} was submitted on the website.
        @endif
    </p>
    <table>
        <tbody>
            @foreach ($details as $label => $value)
                <tr>
                    <th scope="row" style="text-align: left; vertical-align: top; padding-right: 1rem;">{{ $label }}</th>
                    <td style="white-space: pre-wrap;">{{ $value }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
