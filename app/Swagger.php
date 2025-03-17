<?php
/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Minha API",
 *     description="Documentação da API",
 *     @OA\Contact(
 *         email="joadson22p@gmail.com"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 *
 * @OA\Server(
 *     description="Servidor Local",
 *     url=L5_SWAGGER_CONST_HOST
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 *
 * @OA\Tag(
 *     name="Tarefas",
 *     description="API para gerenciamento de tarefas"
 * )
 */