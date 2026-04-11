<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Fixtures;

use App\Domain\Model\Coordinates;
use App\Domain\Model\Route\Itinerary;
use App\Domain\Model\Route\ItineraryId;
use App\Domain\Model\Route\Modality;
use App\Domain\Model\Route\Route;
use App\Domain\Model\Route\RouteId;
use App\Domain\Model\Route\Segment;
use App\Domain\Model\Route\SegmentId;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RouteFixtures extends Fixture
{
    // Route references
    public const string ROUTE_LITORAL_REFERENCE = 'route-litoral';
    public const string ROUTE_INTERIOR_REFERENCE = 'route-interior';
    public const string ROUTE_MUNTANYA_REFERENCE = 'route-muntanya';

    // Itinerary references
    public const string ITINERARY_LITORAL_A_REFERENCE = 'itinerary-litoral-a';
    public const string ITINERARY_LITORAL_B_REFERENCE = 'itinerary-litoral-b';
    public const string ITINERARY_INTERIOR_A_REFERENCE = 'itinerary-interior-a';
    public const string ITINERARY_INTERIOR_B_REFERENCE = 'itinerary-interior-b';
    public const string ITINERARY_MUNTANYA_A_REFERENCE = 'itinerary-muntanya-a';

    // Segment references
    public const string SEGMENT_LITORAL_A1_REFERENCE = 'segment-litoral-a1';
    public const string SEGMENT_LITORAL_A2_REFERENCE = 'segment-litoral-a2';
    public const string SEGMENT_LITORAL_A3_REFERENCE = 'segment-litoral-a3';
    public const string SEGMENT_LITORAL_B1_REFERENCE = 'segment-litoral-b1';
    public const string SEGMENT_LITORAL_B2_REFERENCE = 'segment-litoral-b2';
    public const string SEGMENT_INTERIOR_A1_REFERENCE = 'segment-interior-a1';
    public const string SEGMENT_INTERIOR_A2_REFERENCE = 'segment-interior-a2';
    public const string SEGMENT_INTERIOR_B1_REFERENCE = 'segment-interior-b1';
    public const string SEGMENT_MUNTANYA_A1_REFERENCE = 'segment-muntanya-a1';
    public const string SEGMENT_MUNTANYA_A2_REFERENCE = 'segment-muntanya-a2';

    public function load(ObjectManager $manager): void
    {
        // ── Route 1: Litoral (has capacity, partially full) ───────────────
        $routeLitoral = new Route(
            id: RouteId::generate(),
            name: 'Correllengua Litoral 2026',
            description: 'Recorregut pel litoral català, des de Blanes fins a Vilanova i la Geltrú, passant pels municipis costaners.',
            position: 1,
            startsAt: new DateTimeImmutable('2026-04-25'),
        );

        $itinerarLitoralA = new Itinerary(
            id: ItineraryId::generate(),
            route: $routeLitoral,
            name: 'Itinerari A – Costa Brava',
            position: 1,
        );

        $itinerarLitoralB = new Itinerary(
            id: ItineraryId::generate(),
            route: $routeLitoral,
            name: 'Itinerari B – Costa Daurada',
            position: 2,
        );

        // Segment with capacity that will be full
        $segLitoralA1 = new Segment(
            id: SegmentId::generate(),
            itinerary: $itinerarLitoralA,
            position: 1,
            start: new Coordinates(41.6749, 2.7921),   // Blanes
            end: new Coordinates(41.7117, 2.8364),      // Lloret de Mar
            capacity: 3,                                // Deliberately small so fixtures can fill it
            modality: Modality::WALK,
            startTime: new DateTimeImmutable('2026-04-25 09:00:00'),
        );

        $segLitoralA2 = new Segment(
            id: SegmentId::generate(),
            itinerary: $itinerarLitoralA,
            position: 2,
            start: new Coordinates(41.7117, 2.8364),   // Lloret de Mar
            end: new Coordinates(41.7804, 2.9352),      // Tossa de Mar
            capacity: 100,
            modality: Modality::BIKE,
            startTime: new DateTimeImmutable('2026-04-25 10:30:00'),
        );

        $segLitoralA3 = new Segment(
            id: SegmentId::generate(),
            itinerary: $itinerarLitoralA,
            position: 3,
            start: new Coordinates(41.7804, 2.9352),   // Tossa de Mar
            end: new Coordinates(41.8031, 2.9985),      // Sant Feliu de Guíxols
            capacity: null,                             // Unlimited capacity
            modality: Modality::MIXED,
            startTime: new DateTimeImmutable('2026-04-25 12:00:00'),
        );

        $segLitoralB1 = new Segment(
            id: SegmentId::generate(),
            itinerary: $itinerarLitoralB,
            position: 1,
            start: new Coordinates(41.2284, 1.7256),   // Vilanova i la Geltrú
            end: new Coordinates(41.2180, 1.6328),      // Cubelles
            capacity: 80,
            modality: Modality::WALK,
            startTime: new DateTimeImmutable('2026-04-25 09:00:00'),
        );

        $segLitoralB2 = new Segment(
            id: SegmentId::generate(),
            itinerary: $itinerarLitoralB,
            position: 2,
            start: new Coordinates(41.2180, 1.6328),   // Cubelles
            end: new Coordinates(41.1881, 1.5308),      // Calafell
            capacity: 60,
            modality: Modality::BIKE,
            startTime: new DateTimeImmutable('2026-04-25 10:30:00'),
        );

        $manager->persist($routeLitoral);
        $manager->persist($itinerarLitoralA);
        $manager->persist($itinerarLitoralB);
        $manager->persist($segLitoralA1);
        $manager->persist($segLitoralA2);
        $manager->persist($segLitoralA3);
        $manager->persist($segLitoralB1);
        $manager->persist($segLitoralB2);

        // ── Route 2: Interior ─────────────────────────────────────────────
        $routeInterior = new Route(
            id: RouteId::generate(),
            name: 'Correllengua Interior 2026',
            description: 'Recorregut per les terres de l\'interior, des de Vic fins a Lleida, travessant la plana central catalana.',
            position: 2,
            startsAt: new DateTimeImmutable('2026-04-25'),
        );

        $itinerarInteriorA = new Itinerary(
            id: ItineraryId::generate(),
            route: $routeInterior,
            name: 'Itinerari A – Osona i el Bages',
            position: 1,
        );

        $itinerarInteriorB = new Itinerary(
            id: ItineraryId::generate(),
            route: $routeInterior,
            name: 'Itinerari B – Segarra i Urgell',
            position: 2,
        );

        $segInteriorA1 = new Segment(
            id: SegmentId::generate(),
            itinerary: $itinerarInteriorA,
            position: 1,
            start: new Coordinates(41.9301, 2.2547),   // Vic
            end: new Coordinates(41.7279, 1.8283),      // Manresa
            capacity: 150,
            modality: Modality::BIKE,
            startTime: new DateTimeImmutable('2026-04-25 09:00:00'),
        );

        $segInteriorA2 = new Segment(
            id: SegmentId::generate(),
            itinerary: $itinerarInteriorA,
            position: 2,
            start: new Coordinates(41.7279, 1.8283),   // Manresa
            end: new Coordinates(41.6183, 1.6119),      // Igualada
            capacity: 120,
            modality: Modality::WALK,
            startTime: new DateTimeImmutable('2026-04-25 11:00:00'),
        );

        $segInteriorB1 = new Segment(
            id: SegmentId::generate(),
            itinerary: $itinerarInteriorB,
            position: 1,
            start: new Coordinates(41.7134, 1.3984),   // Cervera
            end: new Coordinates(41.6176, 0.9862),      // Tàrrega
            capacity: null,
            modality: Modality::MIXED,
            startTime: new DateTimeImmutable('2026-04-25 09:00:00'),
        );

        $manager->persist($routeInterior);
        $manager->persist($itinerarInteriorA);
        $manager->persist($itinerarInteriorB);
        $manager->persist($segInteriorA1);
        $manager->persist($segInteriorA2);
        $manager->persist($segInteriorB1);

        // ── Route 3: Muntanya (future, no registrations yet) ─────────────
        $routeMuntanya = new Route(
            id: RouteId::generate(),
            name: 'Correllengua Muntanya 2026',
            description: 'Recorregut pels Pirineus i el Prepirineu, des de Puigcerdà fins a Sort, per les valls més emblemàtiques.',
            position: 3,
            startsAt: new DateTimeImmutable('2026-05-09'),
        );

        $itinerarMuntanyaA = new Itinerary(
            id: ItineraryId::generate(),
            route: $routeMuntanya,
            name: 'Itinerari A – Cerdanya i Pallars',
            position: 1,
        );

        $segMuntanyaA1 = new Segment(
            id: SegmentId::generate(),
            itinerary: $itinerarMuntanyaA,
            position: 1,
            start: new Coordinates(42.4306, 1.9290),   // Puigcerdà
            end: new Coordinates(42.3534, 1.5282),      // Bellver de Cerdanya
            capacity: 50,
            modality: Modality::WALK,
            startTime: new DateTimeImmutable('2026-05-09 09:00:00'),
        );

        $segMuntanyaA2 = new Segment(
            id: SegmentId::generate(),
            itinerary: $itinerarMuntanyaA,
            position: 2,
            start: new Coordinates(42.3534, 1.5282),   // Bellver de Cerdanya
            end: new Coordinates(42.3756, 1.1384),      // Sort
            capacity: 40,
            modality: Modality::BIKE,
            startTime: new DateTimeImmutable('2026-05-09 11:00:00'),
        );

        $manager->persist($routeMuntanya);
        $manager->persist($itinerarMuntanyaA);
        $manager->persist($segMuntanyaA1);
        $manager->persist($segMuntanyaA2);

        $manager->flush();

        // ── Store references ──────────────────────────────────────────────
        $this->addReference(self::ROUTE_LITORAL_REFERENCE, $routeLitoral);
        $this->addReference(self::ROUTE_INTERIOR_REFERENCE, $routeInterior);
        $this->addReference(self::ROUTE_MUNTANYA_REFERENCE, $routeMuntanya);

        $this->addReference(self::ITINERARY_LITORAL_A_REFERENCE, $itinerarLitoralA);
        $this->addReference(self::ITINERARY_LITORAL_B_REFERENCE, $itinerarLitoralB);
        $this->addReference(self::ITINERARY_INTERIOR_A_REFERENCE, $itinerarInteriorA);
        $this->addReference(self::ITINERARY_INTERIOR_B_REFERENCE, $itinerarInteriorB);
        $this->addReference(self::ITINERARY_MUNTANYA_A_REFERENCE, $itinerarMuntanyaA);

        $this->addReference(self::SEGMENT_LITORAL_A1_REFERENCE, $segLitoralA1);
        $this->addReference(self::SEGMENT_LITORAL_A2_REFERENCE, $segLitoralA2);
        $this->addReference(self::SEGMENT_LITORAL_A3_REFERENCE, $segLitoralA3);
        $this->addReference(self::SEGMENT_LITORAL_B1_REFERENCE, $segLitoralB1);
        $this->addReference(self::SEGMENT_LITORAL_B2_REFERENCE, $segLitoralB2);
        $this->addReference(self::SEGMENT_INTERIOR_A1_REFERENCE, $segInteriorA1);
        $this->addReference(self::SEGMENT_INTERIOR_A2_REFERENCE, $segInteriorA2);
        $this->addReference(self::SEGMENT_INTERIOR_B1_REFERENCE, $segInteriorB1);
        $this->addReference(self::SEGMENT_MUNTANYA_A1_REFERENCE, $segMuntanyaA1);
        $this->addReference(self::SEGMENT_MUNTANYA_A2_REFERENCE, $segMuntanyaA2);
    }
}
