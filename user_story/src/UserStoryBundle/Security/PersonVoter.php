namespace UserStoryBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use UserStoryBundle\Entity\Person;
use UserStoryBundle\Entity\User;

class PersonVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, array(self::VIEW, self::EDIT))) {
            return false;
        }

        if (!$subject instanceof Person) {
        return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
    $user = $token->getUser();

    if (!$user instanceof User) {
        return false;
    }

    // you know $subject is a Post object, thanks to supports
    /** @var Person $person */
    $person = $subject;

    switch($attribute) {
        case self::VIEW:
            return $this->canView($post, $user);
        case self::EDIT:
            return $this->canEdit($post, $user);
    }

    throw new \LogicException('This code should not be reached!');
    }

    private function canView(Person $person, User $user)
    {
        if ($this->canEdit($person, $user)) {
            return true;
        }

        return !$post->isPrivate();
    }

    private function canEdit(Person $person, User $user)
    {
    return $user === $person->getOwner();
    }
}