<?php

declare(strict_types=1);

use CA\Example\Factories\CertificateAuthorityFactory;

describe('CertificateAuthorityFactory', function (): void {
    it('creates a factory with default values', function (): void {
        $factory = CertificateAuthorityFactory::new();
        $data = $factory->toArray();

        expect($data)
            ->toBeArray()
            ->toHaveKeys(['common_name', 'subject', 'validity_days', 'key_algorithm', 'is_root', 'parent_ca_id', 'path_length'])
            ->and($data['common_name'])->toBe('Test CA')
            ->and($data['is_root'])->toBeTrue()
            ->and($data['parent_ca_id'])->toBeNull();
    });

    it('creates a root CA with custom values', function (): void {
        $data = CertificateAuthorityFactory::new()
            ->withCommonName('My Root CA')
            ->withOrganization('My Org')
            ->withCountry('US')
            ->withState('California')
            ->withLocality('San Francisco')
            ->withValidityDays(7300)
            ->withKeyAlgorithm('ec-secp384r1')
            ->asRoot()
            ->toArray();

        expect($data['common_name'])->toBe('My Root CA')
            ->and($data['subject']['O'])->toBe('My Org')
            ->and($data['subject']['C'])->toBe('US')
            ->and($data['subject']['ST'])->toBe('California')
            ->and($data['subject']['L'])->toBe('San Francisco')
            ->and($data['validity_days'])->toBe(7300)
            ->and($data['key_algorithm'])->toBe('ec-secp384r1')
            ->and($data['is_root'])->toBeTrue();
    });

    it('creates an intermediate CA', function (): void {
        $parentId = 'parent-ca-uuid';

        $data = CertificateAuthorityFactory::new()
            ->withCommonName('Intermediate CA')
            ->asIntermediate(parentCaId: $parentId)
            ->withPathLength(0)
            ->toArray();

        expect($data['is_root'])->toBeFalse()
            ->and($data['parent_ca_id'])->toBe($parentId)
            ->and($data['path_length'])->toBe(0);
    });

    it('returns immutable clones on each method call', function (): void {
        $original = CertificateAuthorityFactory::new();
        $modified = $original->withCommonName('Modified CA');

        expect($original->toArray()['common_name'])->toBe('Test CA')
            ->and($modified->toArray()['common_name'])->toBe('Modified CA');
    });

    it('creates a CA and returns an array with ca_id and common_name', function (): void {
        $result = CertificateAuthorityFactory::new()
            ->withCommonName('Created CA')
            ->create();

        expect($result)
            ->toBeArray()
            ->toHaveKeys(['ca_id', 'common_name'])
            ->and($result['common_name'])->toBe('Created CA')
            ->and($result['ca_id'])->toBeString()
            ->and($result['ca_id'])->not->toBeEmpty();
    });
});
