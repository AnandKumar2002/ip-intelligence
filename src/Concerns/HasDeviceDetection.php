<?php

namespace Parvion\IpIntelligence\Concerns;

use Parvion\IpIntelligence\Parsers\UserAgentParser;

trait HasDeviceDetection
{
    /**
     * UserAgentParser instance.
     *
     * @var UserAgentParser|null
     */
    protected $uaParser = null;

    /**
     * Get the User Agent Parser instance.
     *
     * @return UserAgentParser
     */
    protected function uaParser(): UserAgentParser
    {
        if (!$this->uaParser) {
            $this->uaParser = new UserAgentParser($this->userAgent() ?? '');
        }

        return $this->uaParser;
    }

    /**
     * Get Raw User Agent String.
     *
     * @return string|null
     */
    public function userAgent(): ?string
    {
        return $this->request->userAgent();
    }

    /**
     * Get Structured User Agent Data.
     *
     * @return array
     */
    public function parsedUserAgent(): array
    {
        return $this->uaParser()->parse();
    }

    /**
     * Get Device Type (Mobile, Tablet, Desktop, TV, Bot).
     *
     * @return string
     */
    public function deviceType(): string
    {
        return $this->uaParser()->getDeviceType();
    }

    /**
     * Get Device Name (e.g. iPhone, Pixel).
     *
     * @return string|null
     */
    public function device(): ?string
    {
        return $this->uaParser()->getDeviceName();
    }

    /**
     * Get Device Brand.
     *
     * @return string|null
     */
    public function deviceBrand(): ?string
    {
        return $this->uaParser()->getDeviceBrand();
    }

    /**
     * Get Device Model.
     *
     * @return string|null
     */
    public function deviceModel(): ?string
    {
        return $this->uaParser()->getDeviceModel();
    }

    /**
     * Get Browser Name.
     *
     * @return string|null
     */
    public function browser(): ?string
    {
        return $this->uaParser()->getBrowser();
    }

    /**
     * Get Browser Version.
     *
     * @return string|null
     */
    public function browserVersion(): ?string
    {
        return $this->uaParser()->getBrowserVersion();
    }

    /**
     * Get Browser Engine.
     *
     * @return string|null
     */
    public function browserEngine(): ?string
    {
        return $this->uaParser()->getBrowserEngine();
    }

    /**
     * Get OS Platform.
     *
     * @return string|null
     */
    public function platform(): ?string
    {
        return $this->uaParser()->getPlatform();
    }

    /**
     * Get Platform Version.
     *
     * @return string|null
     */
    public function platformVersion(): ?string
    {
        return $this->uaParser()->getPlatformVersion();
    }

    /**
     * Get Platform Architecture.
     *
     * @return string|null
     */
    public function platformArchitecture(): ?string
    {
        return $this->uaParser()->getPlatformArchitecture();
    }

    /**
     * Check if Request is a Bot.
     *
     * @return bool
     */
    public function isBot(): bool
    {
        return $this->uaParser()->isBot();
    }

    /**
     * Get Bot Name.
     *
     * @return string|null
     */
    public function botName(): ?string
    {
        return $this->uaParser()->getBotName();
    }
}
