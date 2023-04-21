<?php namespace Miravo\Sendgrid;

use Event;
use Config;
use System\Classes\PluginBase;

/**
 * Plugin Information File
 *
 * @link https://docs.octobercms.com/3.x/extend/system/plugins.html
 */
class Plugin extends PluginBase
{
    /**
     * pluginDetails about this plugin.
     */
    public function pluginDetails()
    {
        return [
            'name' => 'Sendgrid',
            'description' => 'Adds support for Sendgrid as a mail driver.',
            'author' => 'Alec Pinard',
            'icon' => 'icon-envelope-square',
            'homepage' => 'https://github.com/miravo/sendgrid-plugin',
        ];
    }

    /**
     * register method, called when the plugin is first registered.
     */
    public function register()
    {
        // Adds Sendgrid as an available mailing method
        Event::listen('system.mail.getSendModeOptions', function(&$options) {
            $options['sendgrid'] = 'Sendgrid';
        });

        // Adds the "secret" form input, show when Sendgrid is selected
        Event::listen('backend.form.extendFields', function($form) {
            if (
                !$form->getController() instanceof \System\Controllers\Settings ||
                !$form->getModel() instanceof \System\Models\MailSetting
            ) {
                return;
            }

            $form->addTabField('sendgrid_secret', 'miravo.sendgrid::lang.setting.sendgrid_secret.label')
            ->displayAs('sensitive')
            ->commentAbove('miravo.sendgrid::lang.setting.sendgrid_secret.comment')
            ->tab("General")
            ->trigger([
                'action' => 'show',
                'field' => 'send_mode',
                'condition' => 'value[sendgrid]'
            ]);

            $form->addTabField('autoconvert_html_to_plaintext', 'miravo.sendgrid::lang.setting.autoconvert_html_to_plaintext.label')
                ->displayAs('checkbox')
                ->comment('miravo.sendgrid::lang.setting.autoconvert_html_to_plaintext.comment')
                ->tab("General")
                ->trigger([
                    'action' => 'show',
                    'field' => 'send_mode',
                    'condition' => 'value[sendgrid]'
                ]);
        });

        // Sets services configuration (config/services.php) for Sendgrid
        Event::listen('system.mail.applyConfigValues', function($settings) {
            if ($settings->send_mode === 'sendgrid') {
                Config::set('services.sendgrid.secret', $settings->sendgrid_secret);
            }
        });

        // Messages with empty plain text body versions cannot be sent through Sendgrid.
        Event::listen('mailer.prepareSend', function ($mailerInstance, $view, $message) {
            if (empty($message->message->text)) {
                $mailSettings = \System\Models\MailSetting::instance()->value;
                if ($mailSettings) {
                    if (isset($mailSettings['autoconvert_html_to_plaintext']) && $mailSettings['autoconvert_html_to_plaintext']) {
                        $htmlBody = $message->getSymfonyMessage()->getHtmlBody();    
                        // Remove <style> tags and their contents
                        $htmlBodyStripped = preg_replace('/<style[^>]*>.*?<\/style>/is', '', $htmlBody);
                        // Remove any remaining tags
                        $textBody = strip_tags($htmlBodyStripped);
                        $message->getSymfonyMessage()->text($textBody);
                    }
                }
            }
        });
    }
}