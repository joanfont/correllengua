<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Fixtures;

use App\Application\Service\Registration\RegistrationHasher;
use App\Domain\Model\Participant\Participant;
use App\Domain\Model\Participant\ParticipantId;
use App\Domain\Model\Registration\Registration;
use App\Domain\Model\Registration\RegistrationId;
use App\Domain\Model\Route\Segment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ParticipantFixtures extends Fixture implements DependentFixtureInterface
{
    // Participant references
    public const string PARTICIPANT_ANNA_REFERENCE = 'participant-anna';
    public const string PARTICIPANT_MARC_REFERENCE = 'participant-marc';
    public const string PARTICIPANT_LAIA_REFERENCE = 'participant-laia';
    public const string PARTICIPANT_JORDI_REFERENCE = 'participant-jordi';
    public const string PARTICIPANT_MARTA_REFERENCE = 'participant-marta';
    public const string PARTICIPANT_PERE_REFERENCE = 'participant-pere';
    public const string PARTICIPANT_ROSA_REFERENCE = 'participant-rosa';
    public const string PARTICIPANT_MIQUEL_REFERENCE = 'participant-miquel';
    public const string PARTICIPANT_NURIA_REFERENCE = 'participant-nuria';
    public const string PARTICIPANT_CARLES_REFERENCE = 'participant-carles';

    public function __construct(private readonly RegistrationHasher $registrationHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        /** @var Segment $segLitoralA1 */
        $segLitoralA1 = $this->getReference(RouteFixtures::SEGMENT_LITORAL_A1_REFERENCE, Segment::class);
        /** @var Segment $segLitoralA2 */
        $segLitoralA2 = $this->getReference(RouteFixtures::SEGMENT_LITORAL_A2_REFERENCE, Segment::class);
        /** @var Segment $segLitoralA3 */
        $segLitoralA3 = $this->getReference(RouteFixtures::SEGMENT_LITORAL_A3_REFERENCE, Segment::class);
        /** @var Segment $segLitoralB1 */
        $segLitoralB1 = $this->getReference(RouteFixtures::SEGMENT_LITORAL_B1_REFERENCE, Segment::class);
        /** @var Segment $segInteriorA1 */
        $segInteriorA1 = $this->getReference(RouteFixtures::SEGMENT_INTERIOR_A1_REFERENCE, Segment::class);
        /** @var Segment $segInteriorA2 */
        $segInteriorA2 = $this->getReference(RouteFixtures::SEGMENT_INTERIOR_A2_REFERENCE, Segment::class);

        // ── Participants ──────────────────────────────────────────────────

        // Anna: registered in two segments of the same itinerary (litoral A)
        $anna = new Participant(
            id: ParticipantId::generate(),
            name: 'Anna',
            surname: 'Puig',
            email: 'anna.puig@example.cat',
        );

        // Marc: registered in a full segment (litoral A1) — helps fill it
        $marc = new Participant(
            id: ParticipantId::generate(),
            name: 'Marc',
            surname: 'Soler',
            email: 'marc.soler@example.cat',
        );

        // Laia: registered in a full segment (litoral A1) — fills it up
        $laia = new Participant(
            id: ParticipantId::generate(),
            name: 'Laia',
            surname: 'Ferrer',
            email: 'laia.ferrer@example.cat',
        );

        // Jordi: registered across two different routes
        $jordi = new Participant(
            id: ParticipantId::generate(),
            name: 'Jordi',
            surname: 'Mas',
            email: 'jordi.mas@example.cat',
        );

        // Marta: registered in segment with unlimited capacity
        $marta = new Participant(
            id: ParticipantId::generate(),
            name: 'Marta',
            surname: 'Vila',
            email: 'marta.vila@example.cat',
        );

        // Pere: registered in interior route only
        $pere = new Participant(
            id: ParticipantId::generate(),
            name: 'Pere',
            surname: 'Roca',
            email: 'pere.roca@example.cat',
        );

        // Rosa: registered in multiple segments across two routes (max use case)
        $rosa = new Participant(
            id: ParticipantId::generate(),
            name: 'Rosa',
            surname: 'Camps',
            email: 'rosa.camps@example.cat',
        );

        // Miquel: registered only in litoral B
        $miquel = new Participant(
            id: ParticipantId::generate(),
            name: 'Miquel',
            surname: 'Bosch',
            email: 'miquel.bosch@example.cat',
        );

        // Núria: registered in interior, itinerary B (unlimited segment)
        $nuria = new Participant(
            id: ParticipantId::generate(),
            name: 'Núria',
            surname: 'Pla',
            email: 'nuria.pla@example.cat',
        );

        // Carles: no registrations yet (signed up but not enrolled)
        $carles = new Participant(
            id: ParticipantId::generate(),
            name: 'Carles',
            surname: 'Serra',
            email: 'carles.serra@example.cat',
        );

        foreach ([$anna, $marc, $laia, $jordi, $marta, $pere, $rosa, $miquel, $nuria, $carles] as $participant) {
            $manager->persist($participant);
        }

        // ── Registrations ─────────────────────────────────────────────────

        // Fill segLitoralA1 (capacity 3): anna + marc + laia → FULL
        $this->register($manager, $anna, $segLitoralA1);
        $this->register($manager, $marc, $segLitoralA1);
        $this->register($manager, $laia, $segLitoralA1);

        // Anna also in A2 (bike segment)
        $this->register($manager, $anna, $segLitoralA2);

        // Laia also in the unlimited segment A3
        $this->register($manager, $laia, $segLitoralA3);

        // Marta in unlimited segment
        $this->register($manager, $marta, $segLitoralA3);

        // Jordi across two routes: litoral B1 + interior A1
        $this->register($manager, $jordi, $segLitoralB1);
        $this->register($manager, $jordi, $segInteriorA1);

        // Pere in interior only
        $this->register($manager, $pere, $segInteriorA1);
        $this->register($manager, $pere, $segInteriorA2);

        // Rosa in four segments across both routes
        $this->register($manager, $rosa, $segLitoralA2);
        $this->register($manager, $rosa, $segLitoralB1);
        $this->register($manager, $rosa, $segInteriorA1);
        $this->register($manager, $rosa, $segInteriorA2);

        // Miquel in litoral B only
        $this->register($manager, $miquel, $segLitoralB1);

        // Núria in interior B1 (unlimited)
        $segInteriorB1 = $this->getReference(RouteFixtures::SEGMENT_INTERIOR_B1_REFERENCE, Segment::class);
        $this->register($manager, $nuria, $segInteriorB1);

        // Carles has no registrations

        $manager->flush();

        $this->addReference(self::PARTICIPANT_ANNA_REFERENCE, $anna);
        $this->addReference(self::PARTICIPANT_MARC_REFERENCE, $marc);
        $this->addReference(self::PARTICIPANT_LAIA_REFERENCE, $laia);
        $this->addReference(self::PARTICIPANT_JORDI_REFERENCE, $jordi);
        $this->addReference(self::PARTICIPANT_MARTA_REFERENCE, $marta);
        $this->addReference(self::PARTICIPANT_PERE_REFERENCE, $pere);
        $this->addReference(self::PARTICIPANT_ROSA_REFERENCE, $rosa);
        $this->addReference(self::PARTICIPANT_MIQUEL_REFERENCE, $miquel);
        $this->addReference(self::PARTICIPANT_NURIA_REFERENCE, $nuria);
        $this->addReference(self::PARTICIPANT_CARLES_REFERENCE, $carles);
    }

    private function register(ObjectManager $manager, Participant $participant, Segment $segment): void
    {
        $id = RegistrationId::generate();
        $hash = $this->registrationHasher->hash($id);

        $registration = new Registration(
            id: $id,
            participant: $participant,
            segment: $segment,
            hash: $hash,
        );

        $segment->addRegistration($registration);
        $manager->persist($registration);
    }

    public function getDependencies(): array
    {
        return [RouteFixtures::class];
    }
}
