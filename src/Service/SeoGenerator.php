<?php

namespace App\Service;

use App\Entity\Project;

// src/Service/SeoGenerator.php
class SeoGenerator
{
    public function generateForProject(Project $project): array
    {
        $attributes = [];
        foreach ($project->getValues() as $value) {
            $attributes[] = $value->getAttribute()->getName().':'.$value->getValue();
        }

        return [
            'title' => sprintf("%s | %s | Solutions Clés en Main", 
                $project->getName(), 
                $project->getClient()
            ),
            'description' => sprintf(
                "Projet %s incluant %s. Contactez-nous pour une solution personnalisée.",
                $project->getName(),
                implode(', ', $attributes)
            ),
            'keywords' => implode(', ', $attributes)
        ];
    }
}