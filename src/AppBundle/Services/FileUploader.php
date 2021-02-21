<?php

namespace AppBundle\Services;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
	/** @var LoggerInterface */
	private $logger;

	/** @var string */
	private string $uploadDirectory;

	public function __construct(string $uploadDirectory, LoggerInterface $logger)
	{
		$this->uploadDirectory = $uploadDirectory;
		$this->logger = $logger;
	}

	/**
	 * Handle contact image upload
	 *
	 * @param UploadedFile $file
	 * @return string
	 */
	public function upload(UploadedFile $file): string
	{
		$newFilename = '';

		try {
			$newFilename = time().'-'.uniqid().'.'.$file->guessExtension();
			$file->move($this->getUploadDirectory(), $newFilename);
		} catch (FileException $e) {
			$this->logger->error('Could not move uploaded image.', [
				'message' => $e->getMessage(),
				'filename' => $file->getClientOriginalName(),
				'extension' => $file->guessExtension(),
			]);
		}

		return $newFilename;
	}

	public function getUploadDirectory(): string
	{
		return $this->uploadDirectory;
	}

	public function setUploadDirectory(string $uploadDirectory): string
	{
		return $this->uploadDirectory = $uploadDirectory;
	}
}
