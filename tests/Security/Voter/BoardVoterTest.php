<?php

declare(strict_types=1);

namespace App\Tests\Security\Voter;

use App\Entity\Board;
use App\Entity\User;
use App\Entity\UserBoard;
use App\Enum\BoardAction;
use App\Enum\UserBoardPermission;
use App\Security\Voter\BoardVoter;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Uid\Uuid;

final class BoardVoterTest extends TestCase
{
    #[Test]
    public function noUserLoggedIn(): void
    {
        $this->expectException(UserNotFoundException::class);

        // Arrange
        $token = $this->createMock(TokenInterface::class);
        $token->expects($this->once())->method('getUser')->willReturn(null);
        $board = new Board(new Uuid('00000000-0000-0000-0000-000000000000'), 'Lorem ipsum', []);

        // Act
        (new BoardVoter())->vote($token, $board, [BoardAction::Create->value]);

        // Assert should be here but exception we must declare before it happens
    }

    #[Test, DataProvider('givenUserCanAccessBoardCases')]
    public function checkIfGivenUserCanAccessBoard(string $userId, BoardAction $action, int $expected): void
    {
        $token = $this->createMock(TokenInterface::class);
        $token->expects($this->once())->method('getUser')->willReturn(new User(new Uuid($userId), 'user@xample.com', ''));
        $board = new Board(new Uuid('00000000-0000-0000-0000-000000000000'), 'Lorem ipsum', [
            new UserBoard(new Uuid('874aca6a-ce84-45ce-8333-cecda5177691'), new Uuid('887d1e4e-3f55-4641-822c-9f1c27f326d8'), UserBoardPermission::Owner),
            new UserBoard(new Uuid('bf6ef329-a3f5-4dc6-92d2-bc2fa8d187d8'), new Uuid('5e7b2ec4-0173-48ba-ae78-88ef4ffd4d06'), UserBoardPermission::Owner),
            new UserBoard(new Uuid('cf99836b-27e3-4d48-9ace-c62503bca407'), new Uuid('aae88127-2b99-4b83-9f31-a4a1bb1e029e'), UserBoardPermission::Member),
            new UserBoard(new Uuid('f7a47a75-2121-4ca3-b351-a0001304ba31'), new Uuid('df8c0aa1-2bb7-493e-a2b2-af3e0ac5ea8c'), UserBoardPermission::Member),
            new UserBoard(new Uuid('19a4631c-2690-469c-a661-0c4ebbd775f0'), new Uuid('98360fa2-3af6-4d72-869f-096afd4e1084'), UserBoardPermission::Member),
            new UserBoard(new Uuid('16333fe9-27e9-4191-9c99-8b50f869e3b7'), new Uuid('d15b5696-2744-43f6-9a0d-d2f3d2546942'), UserBoardPermission::Member),
            new UserBoard(new Uuid('da777606-5a50-4309-a845-aea43a27aba9'), new Uuid('e67f6f85-c318-45a7-b70d-8b2fd1ca0491'), UserBoardPermission::Member),
            new UserBoard(new Uuid('7c0f9774-6c37-4ae7-8534-4241a7b399e6'), new Uuid('2ff84620-4ccb-4a71-8324-ed0a93bda6a5'), UserBoardPermission::Viewer),
        ]);

        $result = (new BoardVoter())->vote($token, $board, [$action->value]);

        $this->assertSame($expected, $result);
    }

    public static function givenUserCanAccessBoardCases(): iterable
    {
        yield 'User not in board' => ['cf863ff5-b0e1-4c88-83ae-48c0e7731619', BoardAction::Create, VoterInterface::ACCESS_DENIED];
        yield 'User is an Owner and wants to Create' => ['5e7b2ec4-0173-48ba-ae78-88ef4ffd4d06', BoardAction::Create, VoterInterface::ACCESS_GRANTED];
        yield 'User is an Owner and wants to Modify' => ['5e7b2ec4-0173-48ba-ae78-88ef4ffd4d06', BoardAction::Modify, VoterInterface::ACCESS_GRANTED];
        yield 'User is an Owner and wants to View' => ['5e7b2ec4-0173-48ba-ae78-88ef4ffd4d06', BoardAction::View, VoterInterface::ACCESS_GRANTED];
        yield 'User is an Member and wants to Create' => ['98360fa2-3af6-4d72-869f-096afd4e1084', BoardAction::Create, VoterInterface::ACCESS_DENIED];
        yield 'User is an Member and wants to Modify' => ['98360fa2-3af6-4d72-869f-096afd4e1084', BoardAction::Modify, VoterInterface::ACCESS_GRANTED];
        yield 'User is an Member and wants to View' => ['98360fa2-3af6-4d72-869f-096afd4e1084', BoardAction::View, VoterInterface::ACCESS_GRANTED];
        yield 'User is an Viewer and wants to Create' => ['2ff84620-4ccb-4a71-8324-ed0a93bda6a5', BoardAction::Create, VoterInterface::ACCESS_DENIED];
        yield 'User is an Viewer and wants to Modify' => ['2ff84620-4ccb-4a71-8324-ed0a93bda6a5', BoardAction::Modify, VoterInterface::ACCESS_DENIED];
        yield 'User is an Viewer and wants to View' => ['2ff84620-4ccb-4a71-8324-ed0a93bda6a5', BoardAction::View, VoterInterface::ACCESS_GRANTED];
    }
}
