<?php

namespace Travelhood\Library\Provider\GoGlobal\Enum;

use Travelhood\Library\Provider\GoGlobal\EnumAbstract;

class Language extends EnumAbstract
{
	const ARABIC = 'A';
	const CANTONESE = 'C';
	const DANISH = 'DK';
	const ENGLISH = 'E';
	const FRENCH = 'F';
	const GERMAN = 'G';
	const HEBREW = 'H';
	const ITALIAN = 'I';
	const JAPANESE = 'J';
	const KOREAN = 'K';
	const MANDARIN = 'M';
	const PORTUGESE = 'P';
	const RUSSIAN = 'RU';
	const SLOVENIAN = 'SL';
	const SPANISH = 'S';
	const TRADITIONAL_CHINESE = 'CT';
	const TURKISH = 'TR';
	const UKRANIAN = 'UK';

	public static function toIso($value) {
		switch($value) {
			case self::ARABIC: return 'ar';
			case self::CANTONESE: return 'zh';
			case self::ENGLISH: return 'en';
			case self::FRENCH: return 'fr';
			case self::GERMAN: return 'de';
			case self::HEBREW: return 'he';
			case self::ITALIAN: return 'it';
			case self::JAPANESE: return 'jp';
			case self::KOREAN: return 'ko';
			case self::MANDARIN: return 'zh';
			case self::PORTUGESE: return 'pt';
			case self::SPANISH: return 'es';
			case self::TRADITIONAL_CHINESE: return 'zh';
		}
		return strtolower($value);
	}

	public function fromIso($code) {
		$code = strtolower($code);
		switch($code) {
			case 'ar': return self::ARABIC;
			case 'zh': return self::MANDARIN;
			case 'fr': return self::FRENCH;
			case 'de': return self::GERMAN;
			case 'he': return self::HEBREW;
			case 'it': return self::ITALIAN;
			case 'jp': return self::JAPANESE;
			case 'ko': return self::KOREAN;
			case 'pt': return self::PORTUGESE;
			case 'es': return self::SPANISH;
			case 'dk': return self::DANISH;
			case 'ru': return self::RUSSIAN;
			case 'sl': return self::SLOVENIAN;
			case 'tr': return self::TURKISH;
			case 'uk': return self::UKRANIAN;
		}
		return self::ENGLISH;
	}
}