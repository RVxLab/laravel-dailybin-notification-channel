<?php

declare(strict_types=1);

namespace RVxLab\DailyBinNotificationChannel\Messages;

use Illuminate\Contracts\Support\Arrayable;

/**
 * A message for Dailybin
 *
 * @see https://dailybin.dev/docs
 *
 * @implements Arrayable<string, mixed>
 */
class DailyBinMessage implements Arrayable
{
    /**
     * The section for the message
     *
     * A section is required and must be at most 64 characters
     */
    public string $section = '';

    /**
     * The markdown contents of the message
     *
     * Content is required and must be at most 20kb
     */
    public string $content = '';

    /**
     * The agent name or identifier for the message
     *
     * If not null, must be 1-80 characters
     */
    public ?string $source = null;


    /**
     * Set the section for the message
     *
     * Must be at most 64 characters
     *
     * @return $this
     */
    public function section(string $section): static
    {
        $this->section = $section;

        return $this;
    }

    /**
     * Set the content for the message
     *
     * Must be at most 20kb
     *
     * @return $this
     */
    public function content(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * The agent or identifier for the message
     *
     * If not null, must be 1-80 characters
     *
     * @return $this
     */
    public function source(?string $source): static
    {
        $this->source = $source;
        return $this;
    }


    public function toArray(): array
    {
        $data = [
            'section' => $this->section,
            'content' => $this->content,
        ];

        if ($this->source) {
            $data['source'] = $this->source;
        }

        return $data;
    }
}
