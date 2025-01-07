<?php

namespace App\Controller;

use App\Entity\Eleve;
use App\Form\EleveType;
use App\Form\NoteType;
use App\Repository\EleveRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class EleveController extends AbstractController
{
    /**
     * @Route("/eleve/{id}/fiche", name="fiche_stagiaire")
     */
    public function ficheStagiaire(int $id, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'élève/stagiaire par son ID
        $stagiaire = $entityManager->getRepository(Eleve::class)->find($id);

        // S'assurer que l'élève existe
        if (!$stagiaire) {
            throw $this->createNotFoundException('Le stagiaire demandé n\'existe pas.');
        }

        // Rendre une vue et passer les données du stagiaire
        return $this->render('eleve/fiche.html.twig', [
            'nomStagiaire' => $stagiaire->getNom(),
        ]);
    }

    // Méthode pour afficher un élève spécifique
    #[Route('/eleve/{id}', name: 'eleve_show', requirements: ['id' => '\d+'])]
    public function show(Eleve $eleve): Response
    {
        return $this->render('eleve/show.html.twig', [
            'eleve' => $eleve,
        ]);

    }

    // Méthode pour la liste des élèves
    #[Route('/eleve', name: 'eleve_list')]
    public function list(EleveRepository $eleveRepository): Response
    {
        $eleve = $eleveRepository->findAll(); // Récupère tous les élèves

        $response = $this->render('eleve/list.html.twig', [
            'eleve' => $eleve, // Passer la liste des élèves au template Twig
        ]);

        // Désactiver le cache HTTP pour cette réponse
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');

        return $response;
    }

    #[Route('/eleve/new', name: 'eleve_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $eleve = new Eleve();
        $form = $this->createForm(EleveType::class, $eleve);

//        $form->handleRequest($request); // Gère la soumission du formulaire
//
//        // Vérification de la soumission et de sa validité
//        if ($form->isSubmitted() && !$form->isValid()) {
//            $errors = $form->getErrors(true, false);
//            foreach ($form->getErrors(true, false) as $error) {
//                dump($error->getMessage());
//            }
//            $this->addFlash('error', 'Le formulaire contient des erreurs.');
//        }
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            // Gestion de l'upload de l'avatar
//            $avatarFile = $form->get('avatar')->getData();
//
//            if ($avatarFile) {
//                $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
//                $safeFilename = $slugger->slug($originalFilename);
//                $newFilename = $safeFilename . '-' . uniqid() . '.' . $avatarFile->guessExtension();
//
//                try {
//                    $avatarFile->move(
//                        $this->getParameter('avatars_directory'),
//                        $newFilename
//                    );
//                } catch (FileException $e) {
//                    $this->addFlash('error', 'Erreur lors de l\'upload de la photo.');
//                    return $this->redirectToRoute('eleve_new');
//                }
//
//                $eleve->setAvatar($newFilename);
//            }
//
//            // Persiste et sauvegarde en base de données
//            $entityManager->persist($eleve);
//            $entityManager->flush(); // N'oubliez pas de flusher !
//
//            $this->addFlash('success', 'Élève ajouté avec succès.');
//            return $this->redirectToRoute('eleve_list');
//        }
//
//        return $this->render('eleve/new.html.twig', [
//            'form' => $form->createView(),
//        ]);
        $form->handleRequest($request); // Gère la soumission du formulaire

        // Vérification des erreurs si le formulaire n'est pas valide
        if ($form->isSubmitted() && !$form->isValid()) {
            foreach ($form->getErrors(true, false) as $error) {
                dump($error->getMessage()); // Utilisé pour debug (à retirer en prod)
            }
            $this->addFlash('error', 'Le formulaire contient des erreurs.');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'upload de l'avatar
            $avatarFile = $form->get('avatar')->getData();
            if ($avatarFile) {
                // Traitement du fichier uploadé
                $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename); // Nettoie le nom du fichier
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $avatarFile->guessExtension();

                try {
                    // Déplacement du fichier dans le dossier configuré
                    $avatarFile->move(
                        $this->getParameter('avatars_directory'),
                        $newFilename
                    );

                    // Enregistrer le nom du fichier dans la propriété `avatar` de l'entité
                    $eleve->setAvatar($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de la photo.');
                    return $this->redirectToRoute('eleve_new'); // Retour au formulaire
                }
            }

            // Persister l'entité après traitement
            $entityManager->persist($eleve);
            $entityManager->flush();

            $this->addFlash('success', 'L\'élève a été ajouté avec succès.');
            return $this->redirectToRoute('eleve_list'); // Redirection vers une autre page
        }

        return $this->render('eleve/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/eleve/{id}/delete', name: 'eleve_delete', methods: ['POST'])]
    public function delete(Request $request, Eleve $eleve, EntityManagerInterface $entityManager): Response
    {
        // Vérification du token CSRF
        if ($this->isCsrfTokenValid('delete' . $eleve->getId(), $request->request->get('_token'))) {
            $entityManager->remove($eleve);
            $entityManager->flush();

            $this->addFlash('success', 'Élève supprimé avec succès.');
        }

        return $this->redirectToRoute('eleve_list');
    }

}