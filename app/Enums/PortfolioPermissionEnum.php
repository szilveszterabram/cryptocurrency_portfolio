<?php

namespace App\Enums;

enum PortfolioPermissionEnum: string
{
    case NavigateToCreate = 'navigate to portfolio.create';
    case CreatePortfolio = 'create portfolio at portfolio.store';
    case NavigateToShow = 'navigate to portfolio.show';
    case NavigateToEdit = 'navigate to portfolio.edit';
    case UpdatePortfolio = 'edit a portfolio at portfolio.update';
    case DeletePortfolio = 'delete a portfolio at portfolio.destroy';
}
