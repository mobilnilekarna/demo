<?php

namespace App\Enums;

enum FileType: string
{
    case IMAGE = 'image';
    case VIDEO = 'video';
    case DOCUMENT = 'document';
    case AUDIO = 'audio';
    case ARCHIVE = 'archive';
    case PDF = 'pdf';
    case SPREADSHEET = 'spreadsheet';
    case TEXT = 'text';

    // Import/Data processing formats
    case HTML = 'html';
    case XML = 'xml';
    case JSON = 'json';
    case CSV = 'csv';
    case TXT = 'txt';

    case UNKNOWN = 'unknown';

    public function label(): string
    {
        return match($this) {
            self::IMAGE => __('general.file.type.image'),
            self::VIDEO => __('general.file.type.video'),
            self::DOCUMENT => __('general.file.type.document'),
            self::AUDIO => __('general.file.type.audio'),
            self::ARCHIVE => __('general.file.type.archive'),
            self::PDF => __('general.file.type.pdf'),
            self::SPREADSHEET => __('general.file.type.spreadsheet'),
            self::TEXT => __('general.file.type.text'),
            self::HTML => __('general.file.type.html'),
            self::XML => __('general.file.type.xml'),
            self::JSON => __('general.file.type.json'),
            self::CSV => __('general.file.type.csv'),
            self::TXT => __('general.file.type.txt'),
            self::UNKNOWN => __('general.file.type.unknown'),
        };
    }

    public function format(): array
    {
        return match($this) {
            self::IMAGE => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'],
            self::VIDEO => ['mp4', 'avi', 'mov', 'wmv', 'flv', 'mkv', 'webm'],
            self::DOCUMENT => ['doc', 'docx', 'odt', 'rtf'],
            self::AUDIO => ['mp3', 'wav', 'ogg', 'flac', 'm4a'],
            self::ARCHIVE => ['zip', 'rar', '7z', 'tar', 'gz'],
            self::PDF => ['pdf'],
            self::SPREADSHEET => ['xls', 'xlsx', 'ods'],
            self::TEXT => ['txt', 'md', 'log'],
            self::HTML => ['html', 'htm'],
            self::XML => ['xml'],
            self::JSON => ['json'],
            self::CSV => ['csv'],
            self::TXT => ['txt'],
            self::UNKNOWN => [],
        };
    }

    public function mimeTypes(): array
    {
        return match($this) {
            self::IMAGE => ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml', 'image/bmp'],
            self::VIDEO => ['video/mp4', 'video/x-msvideo', 'video/quicktime', 'video/x-ms-wmv', 'video/x-flv', 'video/x-matroska', 'video/webm'],
            self::DOCUMENT => ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.oasis.opendocument.text', 'application/rtf'],
            self::AUDIO => ['audio/mpeg', 'audio/wav', 'audio/ogg', 'audio/flac', 'audio/mp4'],
            self::ARCHIVE => ['application/zip', 'application/x-rar-compressed', 'application/x-7z-compressed', 'application/x-tar', 'application/gzip'],
            self::PDF => ['application/pdf'],
            self::SPREADSHEET => ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.oasis.opendocument.spreadsheet'],
            self::TEXT => ['text/plain', 'text/markdown'],
            self::HTML => ['text/html'],
            self::XML => ['application/xml', 'text/xml'],
            self::JSON => ['application/json'],
            self::CSV => ['text/csv'],
            self::TXT => ['text/plain'],
            self::UNKNOWN => [],
        };
    }

    public static function fromExtension(string $extension): self
    {
        $extension = strtolower($extension);

        foreach (self::cases() as $type) {
            if (in_array($extension, $type->extensions())) {
                return $type;
            }
        }

        return self::OTHER;
    }

    public static function fromMimeType(string $mimeType): self
    {
        foreach (self::cases() as $type) {
            if (in_array($mimeType, $type->mimeTypes())) {
                return $type;
            }
        }

        return self::OTHER;
    }
}
