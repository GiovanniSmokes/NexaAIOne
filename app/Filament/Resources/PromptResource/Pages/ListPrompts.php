<?php

namespace App\Filament\Resources\PromptResource\Pages;

use App\Filament\Resources\PromptResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPrompts extends ListRecords
{
    protected static string $resource = PromptResource::class;
    public function getSubheading(): ?string {
        return __('A place to store your prompts');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
