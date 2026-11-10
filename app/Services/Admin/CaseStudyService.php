<?php

namespace App\Services\Admin;

use App\Models\Admin\CaseStudy;
use App\Models\Admin\Client;
use App\Models\Admin\Testimonial;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CaseStudyService
{
    /**
     * @param  array{search?: string, status?: string}  $filters
     */
    public function getCaseStudies(array $filters): LengthAwarePaginator
    {
        return CaseStudy::query()
            ->with(['client', 'testimonial'])
            ->when(
                isset($filters['search']) && $filters['search'] !== '',
                function (Builder $query) use ($filters): void {
                    $query->where(function (Builder $query) use ($filters): void {
                        $search = $filters['search'];
                        $query->where('title', 'like', '%'.$search.'%')
                            ->orWhere('industry', 'like', '%'.$search.'%')
                            ->orWhere('location', 'like', '%'.$search.'%')
                            ->orWhereHas('client', fn (Builder $clientQuery) => $clientQuery->where('name', 'like', '%'.$search.'%'));
                    });
                },
            )
            ->when(
                isset($filters['status']) && $filters['status'] !== '',
                fn (Builder $query) => $query->where('status', (int) $filters['status']),
            )
            ->orderBy('title')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();
    }

    /**
     * @return array{clients: Collection<int, Client>, testimonials: Collection<int, Testimonial>}
     */
    public function getFormOptions(): array
    {
        return [
            'clients' => Client::query()->orderBy('name')->get(),
            'testimonials' => Testimonial::query()->orderBy('client_name')->orderBy('first_name')->get(),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function createCaseStudy(array $data): CaseStudy
    {
        return CaseStudy::create($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateCaseStudy(CaseStudy $caseStudy, array $data): CaseStudy
    {
        $caseStudy->update($data);

        return $caseStudy->refresh();
    }

    public function deleteCaseStudy(CaseStudy $caseStudy): void
    {
        $caseStudy->delete();
    }

    public function changeStatus(CaseStudy $caseStudy): CaseStudy
    {
        $caseStudy->update(['status' => ! $caseStudy->getRawOriginal('status')]);

        return $caseStudy->refresh();
    }
}
