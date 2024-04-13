<?php

namespace App\Filament\Resources\ArchivesResource\Pages;

use App\Filament\Resources\ArchivesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArchives extends EditRecord
{
    protected static string $resource = ArchivesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
