import React from 'react';
import { Breadcrumb, BreadcrumbItem } from 'reactstrap';

const FormBreadcrumb = (props) => {
    return (
        <div>
            <Breadcrumb>
                <BreadcrumbItem active>Commande </BreadcrumbItem>
                <BreadcrumbItem><a href="#">Tickets </a></BreadcrumbItem>
                <BreadcrumbItem ><a href="#">Paiement </a></BreadcrumbItem>
            </Breadcrumb>
        </div>
    );
};

export default FormBreadcrumb;
