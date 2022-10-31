<?php
// src/Swagger/SwaggerGuarani.php
declare(strict_types=1);

namespace App\Swagger;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class SwaggerGuarani implements NormalizerInterface
{
    private  $decorated;

    public function __construct(NormalizerInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return $this->decorated->supportsNormalization($data, $format);
    }

    public function normalize($object, string $format = null, array $context = [])
    {
        $docs = $this->decorated->normalize($object, $format, $context);

        $docs['components']['schemas']['Blog'] = [
            'type' => 'object',
            'properties' => [
                'blog' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
            ],
        ];
        $docs['components']['schemas']['Variables'] = [
            'type' => 'object',
            'properties' => [
                'dni' => [
                    'type' => 'string',
                    'description' => 'Order Status',
                    'example' => '00000000'
                ]
            ],
        ];

        $GuaranieDocumentation = [
            'paths' => [
                '/isRegular' => [
                    'post' => [
                        'tags' => ['Consulta Guaranie'],
                        'summary' => 'Ver datos de regularidad del alumno',
                        'description' => '',
                        'operationId' => '',
                        'requestBody' => [
                            'description' => 'Insertar Busqueda',
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => '#/components/schemas/Variables',
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            Response::HTTP_OK => [
                                'description' => 'Get datos',
                                'content' => [
                                    'application/json' => [
                                    ],
                                ],
                            ],
                        ]
                        
                    ],
                ],
                '/isIncriptoAnioActual' => [
                    'post' => [
                        'tags' => ['Consulta Guaranie'],
                        'summary' => 'Ver si el alumno esta inscripto actualmente',
                        'description' => '',
                        'operationId' => '',
                        'requestBody' => [
                            'description' => 'Insertar Busqueda',
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => '#/components/schemas/Variables',
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            Response::HTTP_OK => [
                                'description' => 'Get datos',
                                'content' => [
                                    'application/json' => [
                                    ],
                                ],
                            ],
                        ]
                        
                    ],
                ],
                '/isEgresado' => [
                    'post' => [
                        'tags' => ['Consulta Guaranie'],
                        'summary' => 'Ver si el alumn o esta inscripto actualmente',
                        'description' => '',
                        'operationId' => '',
                        'requestBody' => [
                            'description' => 'Insertar Busqueda',
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => '#/components/schemas/Variables',
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            Response::HTTP_OK => [
                                'description' => 'Get datos',
                                'content' => [
                                    'application/json' => [
                                    ],
                                ],
                            ],
                        ]
                        
                    ],
                ],
                '/iscondicionAlumno' => [
                    'post' => [
                        'tags' => ['Alumno'],
                        'summary' => 'Devuelve si el alumno cumple con todos los requisitos para ser regular',
                        'description' => 'Ser regular en almenos una carrera en guarani y estar inscripto en el año actual en esa carrera. Y ademas no tiene que ser egresado en esa carrera.',
                        'operationId' => '',
                        'requestBody' => [
                            'description' => 'Insertar Busqueda',
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => '#/components/schemas/Variables',
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            Response::HTTP_OK => [
                                'description' => 'Get datos',
                                'content' => [
                                    'application/json' => [
                                    ],
                                ],
                            ],
                        ]
                        
                    ],
                ],
                '/datos' => [
                    'get' => [
                        'tags' => ['Consulta Guaranie'],
                        'summary' => 'Ver los',
                        'requestBody' => [
                            'description' => 'ver datos',
                            
                        ],
                        'responses' => [
                            Response::HTTP_OK => [
                                'description' => 'Get datos',
                                'content' => [
                                    'application/json' => [
                                        
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        return array_merge_recursive($docs, $GuaranieDocumentation);
    }
}