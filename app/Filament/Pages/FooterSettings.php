<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class FooterSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Футер';
    protected static ?string $title = 'Настройки футера';
    protected static ?int $navigationSort = 10;
    protected static string $view = 'filament.pages.footer-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $contacts = Setting::getValue('footer_contacts', [
            'phone' => '+7 (999) 123-45-67',
            'email' => 'info@vedma.ru',
            'address' => 'г. Москва, ул. Цветочная, 13',
        ]);

        $socials = Setting::getValue('footer_socials', [
            'telegram_enabled' => true,
            'telegram_url' => '',
            'vk_enabled' => true,
            'vk_url' => '',
            'instagram_enabled' => true,
            'instagram_url' => '',
            'whatsapp_enabled' => false,
            'whatsapp_url' => '',
        ]);

        $this->form->fill(array_merge($contacts, $socials));
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Контакты')->schema([
                    TextInput::make('phone')
                        ->label('Телефон')
                        ->maxLength(30),
                    TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->maxLength(255),
                    TextInput::make('address')
                        ->label('Адрес')
                        ->maxLength(255),
                ])->columns(2),

                Section::make('Соцсети')->schema([
                    Grid::make(2)->schema([
                        Toggle::make('telegram_enabled')->label('Telegram'),
                        TextInput::make('telegram_url')->label('Ссылка Telegram')->url()->placeholder('https://t.me/...'),
                    ]),
                    Grid::make(2)->schema([
                        Toggle::make('vk_enabled')->label('ВКонтакте'),
                        TextInput::make('vk_url')->label('Ссылка VK')->url()->placeholder('https://vk.com/...'),
                    ]),
                    Grid::make(2)->schema([
                        Toggle::make('instagram_enabled')->label('Instagram'),
                        TextInput::make('instagram_url')->label('Ссылка Instagram')->url()->placeholder('https://instagram.com/...'),
                    ]),
                    Grid::make(2)->schema([
                        Toggle::make('whatsapp_enabled')->label('WhatsApp'),
                        TextInput::make('whatsapp_url')->label('Ссылка WhatsApp')->url()->placeholder('https://wa.me/...'),
                    ]),
                ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        Setting::setValue('footer_contacts', [
            'phone' => $state['phone'] ?? '',
            'email' => $state['email'] ?? '',
            'address' => $state['address'] ?? '',
        ]);

        Setting::setValue('footer_socials', [
            'telegram_enabled' => $state['telegram_enabled'] ?? false,
            'telegram_url' => $state['telegram_url'] ?? '',
            'vk_enabled' => $state['vk_enabled'] ?? false,
            'vk_url' => $state['vk_url'] ?? '',
            'instagram_enabled' => $state['instagram_enabled'] ?? false,
            'instagram_url' => $state['instagram_url'] ?? '',
            'whatsapp_enabled' => $state['whatsapp_enabled'] ?? false,
            'whatsapp_url' => $state['whatsapp_url'] ?? '',
        ]);

        Notification::make()
            ->title('Настройки сохранены')
            ->success()
            ->send();
    }
}
