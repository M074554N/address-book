<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AddressBook\Entry;
use AppBundle\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File;

class AddressbookControllerController extends Controller
{
	/** @var LoggerInterface */
	private $logger;

	/** @var EntityManagerInterface */
	private $em;

	/** @var FileUploader */
	private $fileUploader;

	public function __construct(
		EntityManagerInterface $em,
		LoggerInterface $logger,
		FileUploader $fileUploader
	) {
		$this->logger = $logger;
		$this->em = $em;
		$this->fileUploader = $fileUploader;
	}

	/**
	 * @Route("/all")
	 */
	public function indexAction()
	{
		$entries = $this->em->getRepository(Entry::class)->findAll();

		return $this->render('@App/AddressbookController/index.html.twig', [
			'entries' => $entries,
		]);
	}

	/**
     * @Route("/create")
     */
    public function createAction(Request $request)
    {
    	$form = $this->createFormBuilder(new Entry())
			->add('firstname', TextType::class, [
				'label' => 'First Name',
			])
			->add('lastname', TextType::class, [
				'label' => 'Last Name',
			])
			->add('street', TextType::class)
			->add('zip', TextType::class)
			->add('city', TextType::class)
			->add('country', CountryType::class)
			->add('email', EmailType::class)
			->add('phone', TelType::class)
			->add('birthday', BirthdayType::class)
			->add('image', FileType::class, [
				'required' => false,
				'constraints' => [
					new File([
						'maxSize' => '2048k',
						'mimeTypes' => [
							'image/jpeg',
							'image/png',
							'image/gif',
						],
						'mimeTypesMessage' => 'Please upload a valid image',
					])
				],
			])
			->add('save', SubmitType::class, [
				'label' => 'Create Contact',
			])
			->getForm();

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$entry = $form->getData();

			if ($form->get('image')->getData()) {
				$uploadedImageName = $this->fileUploader->upload($form->get('image')->getData());

				if ($uploadedImageName) {
					$entry->setImage($uploadedImageName);
				}
			}

			$this->em->persist($entry);
			$this->em->flush();
			return $this->redirect('/all');
		}

        return $this->render('@App/AddressbookController/create.html.twig', [
        	'form' => $form->createView(),
		]);
    }

    /**
     * @Route("/show/{id}", requirements={"id"="\d+"})
     */
    public function showAction($id)
    {
    	$entry = $this->em->getRepository(Entry::class)->find($id);

		$this->checkIfContactExists($entry);

        return $this->render('@App/AddressbookController/show.html.twig', [
        	'entry' => $entry,
		]);
    }

	/**
	 * @Route("/edit/{id}", methods={"GET","POST"})
	 * @param $id
	 * @param Request $request
	 * @return RedirectResponse|Response|null
	 */
    public function editAction($id, Request $request)
    {
    	$imageFile = null;
		$entry = $this->em->getRepository(Entry::class)->find($id);

		if ($entry->getImage()) {
			$imagePath = $this->getParameter('upload_dir') . '/' . $entry->getImage();
			$imageFile = new \Symfony\Component\HttpFoundation\File\File($imagePath);
		}

		$this->checkIfContactExists($entry);

		$form = $this->createFormBuilder($entry)
			->add('firstname', TextType::class, [
				'label' => 'First Name',
			])
			->add('lastname', TextType::class, [
				'label' => 'Last Name',
			])
			->add('street', TextType::class)
			->add('zip', TextType::class)
			->add('city', TextType::class)
			->add('country', CountryType::class)
			->add('email', EmailType::class)
			->add('phone', TelType::class)
			->add('birthday', BirthdayType::class)
			->add('image', FileType::class, [
				'data_class' => null,
				'required' => false,
				'constraints' => [
					new File([
						'maxSize' => '2048k',
						'mimeTypes' => [
							'image/jpeg',
							'image/png',
							'image/gif',
						],
						'mimeTypesMessage' => 'Please upload a valid image',
					])
				],
			])
			->add('save', SubmitType::class, [
				'label' => 'Update Contact',
			])
			->getForm();


		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$formData = $form->getData();

			if ($form->has('image') && $form->get('image')->isEmpty() && $imageFile) {
				$formData->setImage($imageFile->getFilename());
			}

			if ($form->has('image') && !$form->get('image')->isEmpty()) {
				$uploadedImageName = $this->fileUploader->upload($form->get('image')->getData());

				if ($uploadedImageName) {
					$formData->setImage($uploadedImageName);
				}
			}

			$this->em->persist($formData);
			$this->em->flush();
			return $this->redirect('/all');
		}

        return $this->render('@App/AddressbookController/edit.html.twig', [
        	'entry' => $entry,
        	'form' => $form->createView(),
		]);
    }

	/**
	 * @Route("/delete/{id}", requirements={"id"="\d+"})
	 */
    public function deleteAction(int $id)
    {
		$entry = $this->em->getRepository(Entry::class)->find($id);

		$this->checkIfContactExists($entry);

		$this->em->remove($entry);
		$this->em->flush();

        return $this->redirect('/all');
    }

	/**
	 * Check if a contact is not null or throw an exception
	 *
	 * @param $contact object|null
	 * @throws NotFoundHttpException
	 */
    private function checkIfContactExists(?object $contact): void
	{
		if (!$contact) {
			// TODO: show 404 error
			throw new NotFoundHttpException('Contact not found');
		}
	}

}
