<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Enums;

use AdityaZanjad\Validator\Base\Enum;

use function AdityaZanjad\Validator\Utils\str_after;

/**
 * Common MIME types based on the list from MDN.
 * Source: https://developer.mozilla.org/en-US/docs/Web/HTTP/Guides/MIME_types/Common_types
 *
 * PHP class structure generated with assistance from Google AI.
 *
 * @version 1.0
 */
class MimeType extends Enum
{
    public const AAC            =   "audio/aac";
    public const ABW            =   "application/x-abiword";
    public const APNG           =   "image/apng";
    public const ARC            =   "application/x-freearc";
    public const AVIF           =   "image/avif";
    public const AVI            =   "video/x-msvideo";
    public const AZW            =   "application/vnd.amazon.ebook";
    public const BIN            =   "application/octet-stream";
    public const BMP            =   "image/bmp";
    public const BZ             =   "application/x-bzip";
    public const BZ2            =   "application/x-bzip2";
    public const CDA            =   "application/x-cdf";
    public const CSH            =   "application/x-csh";
    public const CSS            =   "text/css";
    public const CSV            =   "text/csv";
    public const DOC            =   "application/msword";
    public const DOCX           =   "application/vnd.openxmlformats-officedocument.wordprocessingml.document";
    public const EOT            =   "application/vnd.ms-fontobject";
    public const EPUB           =   "application/epub+zip";
    public const GZ             =   "application/gzip";
    public const GIF            =   "image/gif";
    public const HTM            =   "text/html";
    public const HTML           =   "text/html";
    public const ICO            =   "image/vnd.microsoft.icon";
    public const ICS            =   "text/calendar";
    public const JAR            =   "application/java-archive";
    public const JPEG           =   "image/jpeg";
    public const JPG            =   "image/jpeg";
    public const JS             =   "text/javascript";
    public const JSON           =   "application/json";
    public const JSONLD         =   "application/ld+json";
    public const MID            =   "audio/midi";
    public const MIDI           =   "audio/midi";
    public const MJS            =   "text/javascript";
    public const MP3            =   "audio/mpeg";
    public const MP4            =   "video/mp4";
    public const MPEG           =   "video/mpeg";
    public const MPKG           =   "application/vnd.apple.installer+xml";
    public const ODP            =   "application/vnd.oasis.opendocument.presentation";
    public const ODS            =   "application/vnd.oasis.opendocument.spreadsheet";
    public const ODT            =   "application/vnd.oasis.opendocument.text";
    public const OGA            =   "audio/ogg";
    public const OGV            =   "video/ogg";
    public const OGX            =   "application/ogg";
    public const OPUS           =   "audio/ogg";
    public const OTF            =   "font/otf";
    public const PNG            =   "image/png";
    public const PDF            =   "application/pdf";
    public const PHP            =   "text/html";
    public const PPT            =   "application/vnd.ms-powerpoint";
    public const PPTX           =   "application/vnd.openxmlformats-officedocument.presentationml.presentation";
    public const RAR            =   "application/vnd.rar";
    public const RTF            =   "application/rtf";
    public const SH             =   "application/x-sh";
    public const SVG            =   "image/svg+xml";
    public const TAR            =   "application/x-tar";
    public const TIF            =   "image/tiff";
    public const TIFF           =   "image/tiff";
    public const TS             =   "video/mp2t";
    public const TTF            =   "font/ttf";
    public const TXT            =   "text/plain";
    public const VSD            =   "application/vnd.visio";
    public const WAV            =   "audio/wav";
    public const WEBA           =   "audio/webm";
    public const WEBM           =   "video/webm";
    public const WEBP           =   "image/webp";
    public const WOFF           =   "font/woff";
    public const WOFF2          =   "font/woff2";
    public const XHTML          =   "application/xhtml+xml";
    public const XLS            =   "application/vnd.ms-excel";
    public const XLSX           =   "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
    public const XML            =   "application/xml";
    public const XUL            =   "application/vnd.mozilla.xul+xml";
    public const ZIP            =   "application/zip";
    public const ___3GP_VIDEO   =   "video/3gpp";
    public const ___3GP_AUDIO   =   "audio/3gpp";
    public const ___3G2_VIDEO   =   "video/3gpp2";
    public const ___3G2_AUDIO   =   "audio/3gpp2";
    public const ___7Z          =   "application/x-7z-compressed";


    /**
     * @inheritDoc
     */
    public static function resolveName(string $name)
    {
        if (str_starts_with($name, '___')) {
            $name = str_after($name, '___');
        }

        switch ($name) {
            case '3GP_AUDIO':
            case '3GP_VIDEO':
                $name = '3GP';
                break;

            case '3G2_AUDIO':
            case '3G2_VIDEO':
                $name = '3g2';
                break;

            default:
                # No Action
                break;
        }

        return $name;
    }
}
