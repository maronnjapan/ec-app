import UUID from 'uuidjs';

export function createUuid(){
    return UUID.generate();
}