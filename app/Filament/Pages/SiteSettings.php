<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class SiteSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $view = 'filament.pages.site-settings';
    protected static ?string $title = 'Site Settings';
    protected static ?string $navigationGroup = 'CMS';
    public $site_title;
    public $site_tagline;
    public $site_description;
    public $site_address;
    public $site_copyright;
    public $site_logo;
    public $site_footer_logo;
    public $site_favicon;
    public $site_phone;
    public $site_email;
    public $site_whatsapp;

    public function mount()
    {
        $this->form->fill([
            'site_title' => getSetting('site_title'),
            'site_tagline' => getSetting('site_tagline'),
            'site_description' => getSetting('site_description'),
            'site_address' => getSetting('site_address'),
            'site_copyright' => getSetting('site_copyright'),
            'site_logo' => getSetting('site_logo'),
            'site_footer_logo' => getSetting('site_footer_logo'),
            'site_favicon' => getSetting('site_favicon'),
            'site_phone' => getSetting('site_phone'),
            'site_email' => getSetting('site_email'),
            'site_whatsapp' => getSetting('site_whatsapp'),

        ]);
    }
    public function save()
    {
        $state = $this->form->getState();
        $site_logo = $state['site_logo'];
        $site_footer_logo = $state['site_footer_logo'];
        $site_favicon = $state['site_favicon'];

        setSetting('site_title',$this->site_title);
        setSetting('site_tagline',$this->site_tagline);
        setSetting('site_description',$this->site_description);
        setSetting('site_address',$this->site_address);
        setSetting('site_copyright',$this->site_copyright);
        setSetting('site_logo',$site_logo);
        setSetting('site_footer_logo',$site_footer_logo);
        setSetting('site_favicon',$site_favicon);
        setSetting('site_phone',$this->site_phone);
        setSetting('site_email',$this->site_email);
        setSetting('site_whatsapp',$this->site_whatsapp);


        Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();
    }



    protected function getFormSchema(): array
    {
        return [
            Section::make('General')
                ->columns([
                    'sm' => 1,
                    'md' => 2
                ])
                ->schema([
                    TextInput::make('site_title')
                        ->label('Site Title (site_title)')
                        ->placeholder('Enter Site Title'),
                    TextInput::make('site_tagline')
                        ->label('Site tagline (site_tagline)')
                        ->placeholder('Enter Site Tagline'),
                    Textarea::make('site_description')
                        ->label('Site description (site_description)')
                        ->placeholder('Enter Site description')->columnSpan(2),
                    Textarea::make('site_address')
                        ->label('Site address (site_address)')
                        ->placeholder('Enter Site address'),
                    Textarea::make('site_copyright')
                        ->label('Site copyright (site_copyright)')
                        ->placeholder('Enter Site copyright'),

                    FileUpload::make('site_logo')
                        ->label('Site Logo (site_logo)')
                        ->image()
                        ->maxSize(500),
                    FileUpload::make('site_footer_logo')
                        ->label('Site Footer Logo (site_footer_logo)')
                        ->image()
                        ->maxSize(500),
                    FileUpload::make('site_favicon')
                        ->label('Site Favicon (site_favicon)')
                        ->image()
                        ->maxSize( 200) // 200 KB (200 * 1024 bytes)
                        ->imageCropAspectRatio('1:1'),


                ]),
            Section::make('Contact Information')
                ->columns([
                    'sm' => 1,
                    'md' => 2
                ])
                ->schema([
                    TextInput::make('site_phone')
                        ->label('Site phone (site_phone)')
                        ->placeholder('Enter Site phone'),

                    TextInput::make('site_whatsapp')
                        ->label('Site Whatsapp (site_whatsapp)')
                        ->placeholder('Enter Site Whatsapp'),

                    TextInput::make('site_email')
                        ->email()
                        ->label('Site email (site_email)')
                        ->placeholder('Enter Site email'),


                ]),

        ];
    }

}
