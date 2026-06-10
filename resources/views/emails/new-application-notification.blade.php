<!DOCTYPE html>
<html lang="mk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Нова апликација за оглас</title>
    <style>
        @media only screen and (max-width: 600px) {
            .email-wrapper {
                padding: 0 !important;
            }

            .email-card {
                width: 100% !important;
                padding: 20px !important;
                border-radius: 0 !important;
                box-sizing: border-box !important;
            }

            .email-title {
                font-size: 24px !important;
            }

            .email-text,
            .detail-label,
            .detail-value,
            .fallback-link {
                font-size: 16px !important;
                line-height: 1.6 !important;
            }

            .email-button {
                display: block !important;
                width: 100% !important;
                box-sizing: border-box !important;
                text-align: center !important;
            }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f5f5f5; font-family: Arial, Helvetica, sans-serif; color: #1f2937;">
    @php
        $dashboardUrl = 'https://honorarec.mk/employer/login';
        $messageText = filled($application->message) ? $application->message : 'Кандидатот не оставил дополнителна порака.';
        $applicationDate = $application->created_at?->format('d.m.Y H:i');
    @endphp

    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width: 100%; background-color: #f5f5f5; margin: 0; padding: 0;">
        <tr>
            <td align="center" class="email-wrapper" style="padding: 20px 0;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" class="email-card" style="width: 100%; max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; padding: 32px; box-sizing: border-box;">
                    <tr>
                        <td align="center" style="padding-bottom: 28px; border-bottom: 1px solid #e5e7eb;">
                            <div style="font-size: 14px; line-height: 20px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: #059669;">
                                Honorarec.mk
                            </div>
                            <h1 class="email-title" style="margin: 14px 0 0; font-size: 28px; line-height: 1.3; font-weight: 700; color: #111827;">
                                Нова апликација за оглас
                            </h1>
                        </td>
                    </tr>
                    <tr>
                        <td class="email-text" style="padding-top: 28px; font-size: 16px; line-height: 1.7; color: #374151;">
                            <p style="margin: 0 0 16px;">Здраво {{ $company->name }},</p>
                            <p style="margin: 0 0 8px;">Имате нова апликација за вашиот оглас:</p>
                            <p style="margin: 0 0 24px; font-size: 22px; line-height: 1.4; font-weight: 700; color: #111827; text-align: center;">
                                "{{ $jobListing->title }}"
                            </p>

                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width: 100%; border: 1px solid #e5e7eb; border-radius: 10px; background-color: #f9fafb; margin-bottom: 24px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 16px; font-size: 18px; line-height: 1.5; font-weight: 700; color: #111827;">Податоци за кандидатот</p>

                                        <p style="margin: 0 0 12px;">
                                            <span class="detail-label" style="display: block; font-size: 13px; line-height: 1.5; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: #6b7280;">Име и презиме</span>
                                            <span class="detail-value" style="display: block; font-size: 16px; line-height: 1.7; color: #111827;">{{ $application->full_name }}</span>
                                        </p>

                                        <p style="margin: 0 0 12px;">
                                            <span class="detail-label" style="display: block; font-size: 13px; line-height: 1.5; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: #6b7280;">Телефон</span>
                                            <span class="detail-value" style="display: block; font-size: 16px; line-height: 1.7; color: #111827;">{{ $application->phone }}</span>
                                        </p>

                                        <p style="margin: 0;">
                                            <span class="detail-label" style="display: block; font-size: 13px; line-height: 1.5; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: #6b7280;">Град</span>
                                            <span class="detail-value" style="display: block; font-size: 16px; line-height: 1.7; color: #111827;">{{ $application->city }}</span>
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 0 0 8px; font-size: 18px; line-height: 1.5; font-weight: 700; color: #111827;">Порака од кандидатот</p>
                            <p style="margin: 0 0 24px; padding: 18px 20px; background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px; white-space: pre-line;">{{ $messageText }}</p>

                            <p style="margin: 0 0 8px; font-size: 18px; line-height: 1.5; font-weight: 700; color: #111827;">CV</p>
                            @if ($cvUrl)
                                <p style="margin: 0 0 24px; padding: 18px 20px; background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px;">
                                    <a href="{{ $cvUrl }}" style="color: #059669; text-decoration: none; font-weight: 700;">Отвори CV</a><br>
                                    <span class="fallback-link" style="display: block; margin-top: 10px; word-break: break-word; color: #4b5563;">{{ $cvUrl }}</span>
                                </p>
                            @else
                                <p style="margin: 0 0 24px; padding: 18px 20px; background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px;">Кандидатот нема прикачено CV.</p>
                            @endif

                            <p style="margin: 0 0 24px;">
                                <span class="detail-label" style="display: block; font-size: 13px; line-height: 1.5; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: #6b7280;">Апликацијата е испратена на</span>
                                <span class="detail-value" style="display: block; font-size: 16px; line-height: 1.7; color: #111827;">{{ $applicationDate }}</span>
                            </p>

                            <p style="margin: 0 0 24px;">За да ја прегледате апликацијата, најавете се во вашиот employer dashboard.</p>

                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" style="margin: 0 auto 24px;">
                                <tr>
                                    <td align="center" bgcolor="#059669" style="border-radius: 999px;">
                                        <a href="{{ $dashboardUrl }}" class="email-button" style="display: inline-block; padding: 14px 28px; font-size: 16px; line-height: 1.2; font-weight: 700; color: #ffffff; text-decoration: none; border-radius: 999px; background-color: #059669;">
                                            Најави се во employer dashboard
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 0 0 6px; color: #4b5563;">Доколку копчето не работи, отворете го овој линк:</p>
                            <p style="margin: 0 0 28px; word-break: break-word;">
                                <a href="{{ $dashboardUrl }}" class="fallback-link" style="color: #059669; text-decoration: none;">{{ $dashboardUrl }}</a>
                            </p>

                            <p style="margin: 0; color: #4b5563;">Поздрав,<br>Honorarec.mk</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
