<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\ProgramType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/programs",name="program_")
 */
class ProgramController extends AbstractController
{
    /**
     * Show all rows from Program’s entity.
     *
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
        ->getRepository(Program::class)
        ->findAll();

        return $this->render('program/index.html.twig',
            ['programs' => $programs]
        );
    }

    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request): Response
    {
        // Create a new Program Object
        $program = new Program();
        // Create the associated Form
        $form = $this->createForm(ProgramType::class, $program);
        // Get data from HTTP request
        $form->handleRequest($request);
        // Was the form submitted ?
        if ($form->isSubmitted() && $form->isValid()) {
            // Deal with the submitted data
            // Get the Entity Manager
            $entityManager = $this->getDoctrine()->getManager();
            // Persist Program Object
            $entityManager->persist($program);
            // Flush the persisted object
            $entityManager->flush();
            // Finally redirect to categories list
            return $this->redirectToRoute('program_index');
        }
        // Render the form
        return $this->render('category/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Getting a program by id.
     *
     * @Route("/show/{id<^[0-9]+$>}", name="show")
     */
    public function show(Program $program): Response
    {
        // $program = $this->getDoctrine()
        // ->getRepository(Program::class)
        // ->findOneBy(['id' => $id]);

        if (!$program) {
            throw $this->createNotFoundException('No program with found in program\'s table.');
        }

        $seasons = $program->getSeasons();

        return $this->render('program/show.html.twig', [
            'program' => $program,
            'seasons' => $seasons,
        ]);
    }

    /**
     * Show season by program id and season id.
     *
     * @Route("/{program_id}/seasons/{season_id} ", name="show_season", requirements={"program"="\d+", "season"="\d+"})
     * @ParamConverter("program", class="App\Entity\Program", options={"id": "program_id"})
     */
    public function showSeason(program $program, int $season_id)
    {
        $season = $this->getDoctrine()->getRepository(Season::class)->findOneBy(['number' => $season_id, 'program' => $program->getId()]);

        if (!$program) {
            throw $this->createNotFoundException('No program with id : '.$program->getId().' found in program\'s table.');
        }

        if (!$season) {
            // throw $this->createNotFoundException('No program with id : '.$season->getId().' found in season\'s table.');
        }

        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
        ]);
    }

    /**
     * @Route("/{program_id}/seasons/{season_id}/episodes/{episode_id}", name="episode_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"program_id": "id"}})
     */
    public function showEpisode(Program $program, int $season_id, int $episode_id)
    {
        $season = $this->getDoctrine()->getRepository(Season::class)->findOneBy(['program' => $program->getId(), 'number' => $season_id]);
        $episode = $this->getDoctrine()->getRepository(Episode::class)->findOneBy(['season' => $season->getId(), 'number' => $episode_id]);

        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
        ]);
    }
}
