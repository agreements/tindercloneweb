<?php

/* All the landing pages must implement this LandingPageInterface */

namespace App\Plugins\LandingPagesPlugin;

interface LandingPageInterface {
	public function contentSections();
	public function imageSections();
	public function linkSections();
	public function languageFile();
}