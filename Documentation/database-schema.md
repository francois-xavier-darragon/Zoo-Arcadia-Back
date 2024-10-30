Habitat
- id
- nom
- description
- shortDescription
|
|--< Image
|   - id
|   - name
|   - originalName
|   - size
|   - mimeType
|   - Files (habitat/enclosure/animal/service)
|
|--< Enclos
    - id
    - nom
    - description
    - shortDescription
    |
    |--< Image
    |
    |--< Animal
        - id
        - nom
        - description
        - état_santé
        |
        |--< Image
        |
        |--< Breed
        |   - id
        |   - nom
        |
        |--< VeterinaryReport
        |   - id
        |   - detail
        |   - date
        |
        |--< Food
            - id
            - quantity
            - mealTime
            - foodType
            - instructions
            |
            |--< FoodAdministration
                - id
                - date
                - quantity
                - notes

User
- id
- email
- firstName
- lastName
- roles
- password
- phone
- address
|
|--< Image (avatar)
|
|--< Notice
|   - id
|   - nickname
|   - comment
|   - status
|
|--< VeterinaryReport
|
|--< FoodAdministration

Service
- id
- nom
- description
- shortDescription
|
|--< Image