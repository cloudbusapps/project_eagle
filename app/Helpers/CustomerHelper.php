<?php
function getStatus($Status = 0)
{
    if ($Status <7) {
        return '<span class="badge rounded-pill bg-secondary">New</span>';
    } else if ($Status == 7 ) {
        return '<span class="badge rounded-pill bg-info">For Assessment</span>';
    } else if ($Status == 8) {
        return '<span class="badge rounded-pill bg-warning">Proposal</span>';
    } else if ($Status == 9){
        return '<span class="badge rounded-pill bg-primary">Signed Proposal</span>';
    } else if ($Status==10){
        return '<span class="badge rounded-pill bg-danger">Closed Lost</span>';
    } else if ($Status==11){
        return '<span class="badge rounded-pill bg-success">Closed</span>';
    }
}