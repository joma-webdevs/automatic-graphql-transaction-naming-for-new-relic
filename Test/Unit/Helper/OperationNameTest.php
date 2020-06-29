<?php
/**
 * @author Jomashop
 */

namespace JomaShop\NewRelicMonitoring\Test\Unit\Helper;

class OperationNameTest extends \PHPUnit\Framework\TestCase
{
    private $objectManager;
    private $operationNameHelper;

    public function setUp()
    {
        $this->objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->operationNameHelper = $this->objectManager
            ->getObject('JomaShop\NewRelicMonitoring\Helper\NewRelicReportData');
    }

    /**
     * @dataProvider dataProviderTestGetOperationNameFromQuery
     */
    public function testGetOperationNameFromQuery($query, $actualOperationName)
    {
        $expectedOperationName = $this->operationNameHelper->getOperationNameFromQuery($query);
        $this->assertEquals($expectedOperationName, $actualOperationName);
    }

    public function dataProviderTestGetOperationNameFromQuery()
    {
        // 1 operation type (query) + operation name        => operation name
        // 2 operation type + operation name with spaces    => operation name
        // 3 operation type (mutation) + operation name     => operation name
        // 4 no operation type / name                       => ''
        // 5 operation type + name + variables              => operation name
        // 6 operation type without name                    => ''

        return [
          [' query category{
            couponCenterCoupons
  (
    search:{
    	field: "coupon_code"
    	value: "coupon20",
    	condition_type: "eq",
  	}
  )
  {
    name,
    total_count,
    items {
      id,
      coupon_code,
      short_title,
      long_title,
      position,
      description,
      start_date,
      end_date
    }
  },
  shippingLeadTime {
    ships,
    messages {
      date_start,
      date_end
    },
    currentCutOffs {
      date,
      time
    }
  }
  
       }', 'category'],

            ['                   query                                          category                    {
            couponCenterCoupons
  (
    search:{
    	field: "coupon_code"
    	value: "coupon20",
    	condition_type: "eq",
  	}
  )
  {
    name,
    total_count,
    items {
      id,
      coupon_code,
      short_title,
      long_title,
      position,
      description,
      start_date,
      end_date
    }
  },
  shippingLeadTime {
    ships,
    messages {
      date_start,
      date_end
    },
    currentCutOffs {
      date,
      time
    }
  }

       }', 'category'],

            // 3
            ['                   mutation                                          createCustomer                    {
            couponCenterCoupons
  (
    search:{
    	field: "coupon_code"
    	value: "coupon20",
    	condition_type: "eq",
  	}
  )
  {
    name,
    total_count,
    items {
      id,
      coupon_code,
      short_title,
      long_title,
      position,
      description,
      start_date,
      end_date
    }
  },
  shippingLeadTime {
    ships,
    messages {
      date_start,
      date_end
    },
    currentCutOffs {
      date,
      time
    }
  }

       }', 'createCustomer'],

            // 4
            ['                       {
            couponCenterCoupons
  (
    search:{
    	field: "coupon_code"
    	value: "coupon20",
    	condition_type: "eq",
  	}
  )
  {
    name,
    total_count,
    items {
      id,
      coupon_code,
      short_title,
      long_title,
      position,
      description,
      start_date,
      end_date
    }
  },
  shippingLeadTime {
    ships,
    messages {
      date_start,
      date_end
    },
    currentCutOffs {
      date,
      time
    }
  }

       }', ''],

            // 5
            ['query category($id: String!, $idNum: Int!, $pageSize: Int!, $currentPage: Int!, $onServer: 
            Boolean!, $filter: ProductAttributeFilterInput!, $sort: ProductAttributeSortInput) {
  category(id: $idNum) {
    id
    description
    name
    url_key
    product_count
    display_mode
    landing_page
    landing_page_identifier
    breadcrumbs {
      category_level
      category_name
      category_url_key
      __typename
    }
    __typename
  }
  categoryList(filters: {ids: {in: [$id]}}) {
    children_count
    featured_filter
    children {
      id
      level
      name
      path
      url_path
      url_key
      product_count
      children {
        id
        level
        name
        path
        url_path
        url_key
        __typename
      }
      __typename
    }
    meta_title @include(if: $onServer)
    meta_keywords @include(if: $onServer)
    meta_description @include(if: $onServer)
    filter_map {
      request_var
      value_string
      url
      __typename
    }
    __typename
  }
  products(pageSize: $pageSize, currentPage: $currentPage, filter: $filter, sort: $sort) {
    aggregations {
      attribute_code
      count
      label
      options {
        label
        value
        count
        swatch_image
        __typename
      }
      __typename
    }
    sort_fields {
      default
      options {
        label
        value
        __typename
      }
      __typename
    }
    items {
      __typename
      id
      name
      msrp
      price_promo_text_grid
      price {
        regularPrice {
          amount {
            currency
            value
            __typename
          }
          __typename
        }
        __typename
      }
      price_range {
        minimum_price {
          regular_price {
            value
            currency
            __typename
          }
          final_price {
            value
            currency
            __typename
          }
          price_promo_text
          msrp_price {
            value
            currency
            __typename
          }
          discount_on_msrp {
            amount_off
            percent_off
            __typename
          }
          __typename
        }
        __typename
      }
      brand_name
      manufacturer
      special_price
      sku
      small_image {
        url
        __typename
      }
      url_key
      active_coupon
      is_preowned
    }
    page_info {
      total_pages
      __typename
    }
    total_count
    __typename
  }
  storeConfig {
    badge_coupon_font_color
    badge_coupon_background
    badge_coupon_text_of_the_badge
    badge_preowned_font_color
    badge_preowned_background
    badge_preowned_text_of_the_badge
    __typename
  }
}', 'category'],

            // 6
            ['                   mutation   {
            couponCenterCoupons
  (
    search:{
    	field: "coupon_code"
    	value: "coupon20",
    	condition_type: "eq",
  	}
  )
  {
    name,
    total_count,
    items {
      id,
      coupon_code,
      short_title,
      long_title,
      position,
      description,
      start_date,
      end_date
    }
  },
  shippingLeadTime {
    ships,
    messages {
      date_start,
      date_end
    },
    currentCutOffs {
      date,
      time
    }
  }

       }', ''],
        ];
    }
}
