<?php
namespace App\Service;

use Mailjet\Client;
use Mailjet\Resources;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MailService
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }
    
    public function send($toEmail, $subject, $content)
    {
        
        $mj = new Client($this->params->get('api_key'), $this->params->get('api_secret_key'), true, ['version' => 'v3.1']);

        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "fxd15130@gmail.com",
                        'Name' => "FXD"
                    ],
                    'To' => [
                        [
                            'Email' => $toEmail,
                        ]
                    ],
                    'TemplateID' => 6265311,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => $content
                ]
            ]
        ];

        $response = $mj->post(Resources::$Email, ['body' => $body]);

        if ($response->success()) {
            $messages = $response->getData();
            if (isset($messages['Messages']) && is_array($messages['Messages']) && !empty($messages['Messages'])) {
                $firstMessage = $messages['Messages'][0];
                return [
                    'success' => true,
                    'status' => $firstMessage['Status'] ?? 'Unknown',
                    'customId' => $firstMessage['CustomID'] ?? '',
                    'to' => $firstMessage['To'] ?? []
                ];
            } else {
                return [
                    'success' => true,
                    'status' => 'Success, but no message details available'
                ];
            }
        } else {
            return [
                'success' => false,
                'error' => $response->getReasonPhrase() ?? 'Unknown error'
            ];
        }
    }
}