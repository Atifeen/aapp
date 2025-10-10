<?php

namespace App\Services;

class RatingCalculator
{
    /**
     * Calculate rating changes for all participants in an exam
     */
    public function calculateRatingChanges(array $participants)
    {
        // Sort participants by score in descending order
        usort($participants, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        $changes = [];
        $totalParticipants = count($participants);

        foreach ($participants as $rank => $participant) {
            $ratingChange = $this->calculateIndividualRatingChange(
                $participant['current_rating'],
                $rank + 1,
                $totalParticipants,
                $participant['score'],
                $participant['max_score']
            );

            $changes[] = [
                'user_id' => $participant['user_id'],
                'old_rating' => $participant['current_rating'],
                'new_rating' => $participant['current_rating'] + $ratingChange,
                'rank_in_contest' => $rank + 1
            ];
        }

        return $changes;
    }

    /**
     * Calculate rating change for an individual participant
     */
    private function calculateIndividualRatingChange(
        int $currentRating,
        int $rank,
        int $totalParticipants,
        float $score,
        float $maxScore
    ): int {
        // Expected rank based on current rating
        $expectedRank = $totalParticipants / 2;
        
        // Performance score based on actual rank
        $rankPerformance = (($totalParticipants - $rank + 1) / $totalParticipants) * 500;
        
        // Score performance
        $scorePerformance = ($score / $maxScore) * 500;
        
        // Calculate rating change
        $ratingChange = (int)(($rankPerformance + $scorePerformance - ($currentRating - 1500)) / 4);
        
        // Limit maximum rating change
        return max(-100, min(100, $ratingChange));
    }
}