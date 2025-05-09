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
use Symfony\Component\HttpFoundation\File\UploadedFile;


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
    //----------------

    #[Route('/eleve/{id}/notes/new', name: 'note_new', methods: ['GET', 'POST'])]
    public function addNote(
        Eleve $eleve,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $note = new \App\Entity\Note(); // Crée une nouvelle instance de Note
        $note->setEleve($eleve); // Associe la note à l'élève spécifique

        $form = $this->createForm(NoteType::class, $note); // Utilise le formulaire NoteType
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($note); // Enregistre la nouvelle note
            $entityManager->flush();

            $this->addFlash('success', 'La note a été ajoutée avec succès.');

            return $this->redirectToRoute('eleve_show', ['id' => $eleve->getId()]);
        }

        return $this->render('eleve/notes/new.html.twig', [
            'form' => $form->createView(),
            'eleve' => $eleve, // Passe l'objet eleve à la vue
        ]);
    }
    //------


    #[Route('/eleve/{id}', name: 'eleve_show', requirements: ['id' => '\d+'])]
    public function show(Eleve $eleve): Response
    {
        return $this->render('eleve/show.html.twig', [
            'eleve' => $eleve,
            'notes' => $eleve->getNotes(), // Récupère les notes associées à cet élève
        ]);
    }

    //----------------
    #[Route('/eleve/{id}/notes', name: 'note_list', methods: ['GET'])]
    public function listNotes(
        Eleve $eleve,
        EntityManagerInterface $entityManager
    ): Response {
        $notes = $entityManager->getRepository(\App\Entity\Note::class)
            ->findBy(['eleve' => $eleve]); // Récupère les notes associées à l'élève

        return $this->render('eleve/notes.html.twig', [
            'eleve' => $eleve,
            'notes' => $notes,
        ]);
    }
    //----------------
    #[Route('/eleve/notes/{id}/delete', name: 'note_delete', methods: ['POST'])]
    public function deleteNote(
        \App\Entity\Note $note,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        // Vérifie le token CSRF pour valider la suppression
        if ($this->isCsrfTokenValid('delete-note' . $note->getId(), $request->request->get('_token'))) {
            $entityManager->remove($note);
            $entityManager->flush();

            $this->addFlash('success', 'La note a été supprimée avec succès.');
        }

        return $this->redirectToRoute('note_list', ['id' => $note->getEleve()->getId()]);
    }
    //------------------
    #[Route('/eleve/{id}/delete', name: 'eleve_delete', methods: ['POST'])]
    public function deleteEleve(
        Eleve $eleve,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $eleve->getId(), $request->request->get('_token'))) {
            $entityManager->remove($eleve);
            $entityManager->flush();

            $this->addFlash('success', 'L\'élève a été supprimé avec succès.');
        }

        return $this->redirectToRoute('eleve_list');
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
//    #[Route('/eleve/new', name: 'eleve_new', methods: ['GET', 'POST'])]
//    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
//    {
//        $eleve = new Eleve(); // Instanciez un nouvel objet Eleve
//        $form = $this->createForm(EleveType::class, $eleve); // Créez le formulaire basé sur EleveType
//        $form->handleRequest($request); // Gère la requête POST du formulaire
//
//        // Vérification si le formulaire est soumis
//        if ($form->isSubmitted()) {
//            // Vérifiez si le formulaire est invalide et affichez les erreurs dans les logs
//            if (!$form->isValid()) {
//                foreach ($form->getErrors(true, false) as $error) {
//                    dump($error->getMessage()); // Déboguez les messages d'erreur
//                }
//                $this->addFlash('error', 'Le formulaire contient des erreurs.');
//            }
//
//            // Si le formulaire est valide
//            if ($form->isSubmitted() && $form->isValid()) {
//                // Récupérez le fichier uploadé depuis le champ `avatar`
//                $avatarFile = $form->get('avatar')->getData();
//
//                // Déboguer pour vérifier la présence du fichier
//                dump($avatarFile); // Doit afficher un objet UploadedFile si tout est correct
//
//                if ($avatarFile) {
//                    // Récupération et construction du nom de fichier sécurisé
//                    $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
//                    $safeFilename = $slugger->slug($originalFilename);
//                    $newFilename = sprintf('%s-%s.%s', $safeFilename, uniqid(), $avatarFile->guessExtension());
//
//                    try {
//                        // Déplacement du fichier vers le répertoire configuré
//                        $avatarFile->move(
//                            $this->getParameter('avatars_directory'), // Vérifiez que ce paramètre est configuré
//                            $newFilename
//                        );
//                        // Mise à jour de l'entité Eleve avec le nom du fichier
//                        $eleve->setAvatar($newFilename);
//                    } catch (FileException $e) {
//                        // Ajoutez un flash pour signaler l'erreur à l'utilisateur
//                        $this->addFlash('error', 'Une erreur s\'est produite lors du téléchargement du fichier.');
//                        return $this->redirectToRoute('eleve_new');
//                    }
//                }
//
//                // Sauvegarde de l'entité Eleve dans la base de données
//                $entityManager->persist($eleve);
//                $entityManager->flush();
//
//                // Confirmation du succès et redirection
//                $this->addFlash('success', 'L\'élève a été ajouté avec succès.');
//                return $this->redirectToRoute('eleve_list');
//            }
//        }
//
//        return $this->render('eleve/new.html.twig', [ // Passage de la vue Twig avec le formulaire
//            'form' => $form->createView(),
//        ]);
//    }
    #[Route('/eleve/new', name: 'eleve_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $eleve = new Eleve();
        $form = $this->createForm(EleveType::class, $eleve);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatarFile = $form->get('avatar')->getData();

            if ($avatarFile) {
                $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$avatarFile->guessExtension();

                try {
                    $avatarFile->move(
                        $this->getParameter('avatars_directory'),
                        $newFilename
                    );
                    $eleve->setAvatar($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors du téléchargement de l\'avatar');
                }
            }

            $entityManager->persist($eleve);
            $entityManager->flush();

            $this->addFlash('success', 'Élève ajouté avec succès');
            return $this->redirectToRoute('eleve_list');
        }

        return $this->render('eleve/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}