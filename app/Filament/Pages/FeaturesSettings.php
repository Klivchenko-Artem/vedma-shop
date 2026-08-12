<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class FeaturesSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationLabel = 'Преимущества';
    protected static ?string $title = 'Описания преимуществ';
    protected static ?int $navigationSort = 11;
    protected static string $view = 'filament.pages.features-settings';

    public ?array $data = [];

    private const TITLES = [
        'Всегда свежие',
        'Доставка 24ч',
        'Ручная сборка',
        'Гарантия',
    ];

    private const DEFAULTS = [
        'Закупаем цветы каждое утро у лучших поставщиков',
        'Привезём в любую точку города в течение суток',
        'Каждый букет собирает флорист вручную',
        'Не понравилось — заменим бесплатно',
    ];

    public function mount(): void
    {
        $descs = Setting::getValue('home_feature_descs', self::DEFAULTS);

        $fill = [];
        foreach ($descs as $i => $desc) {
            $fill["desc_{$i}"] = $desc;
        }

        $this->form->fill($fill);
    }

    public function form(Form $form): Form
    {
        $sections = [];
        foreach (self::TITLES as $i => $title) {
            $sections[] = Section::make($title)->schema([
                Textarea::make("desc_{$i}")
                    ->label('Описание')
                    ->rows(2)
                    ->maxLength(255)
                    ->placeholder(self::DEFAULTS[$i]),
            ]);
        }

        return $form->schema($sections)->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        $descs = [];
        for ($i = 0; $i < 4; $i++) {
            $descs[] = $state["desc_{$i}"] ?? self::DEFAULTS[$i];
        }

        Setting::setValue('home_feature_descs', $descs);

        Notification::make()
            ->title('Настройки сохранены')
            ->success()
            ->send();
    }
}
